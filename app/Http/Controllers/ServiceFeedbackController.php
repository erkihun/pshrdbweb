<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceFeedbackRequest;
use App\Models\Service;
use App\Models\ServiceFeedback;
use Illuminate\Support\Str;

class ServiceFeedbackController extends Controller
{
    public function store(StoreServiceFeedbackRequest $request, string $slug)
    {
        $service = Service::where('is_active', true)->where('slug', $slug)->firstOrFail();

        if ($this->isRateLimited($request->ip(), $service->id)) {
            return back()->withErrors(['rating' => __('common.messages.too_many_feedback')])->withInput();
        }

        $data = $request->validated();
        $data['service_id'] = $service->id;
        $data['locale'] = app()->getLocale();
        $data['ip_hash'] = hash_hmac('sha256', (string) $request->ip(), config('app.key'));
        $data['submitted_at'] = now();
        $data['is_approved'] = false;

        $data['comment'] = $this->cleanComment($data['comment'] ?? null);

        ServiceFeedback::create($data);

        return back()->with('success', __('common.messages.feedback_received'));
    }

    private function isRateLimited(string $ip, string $serviceId): bool
    {
        $ipHash = hash_hmac('sha256', $ip, config('app.key'));

        return ServiceFeedback::where('service_id', $serviceId)
            ->where('ip_hash', $ipHash)
            ->whereDate('submitted_at', now()->toDateString())
            ->exists();
    }

    private function cleanComment(?string $comment): ?string
    {
        if (! $comment) {
            return null;
        }

        $badWords = ['badword1', 'badword2'];
        $cleaned = $comment;
        foreach ($badWords as $word) {
            $cleaned = str_ireplace($word, '***', $cleaned);
        }

        return $cleaned;
    }
}
