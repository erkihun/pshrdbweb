<?php

namespace App\Services;

use App\Mail\DocumentRequestStatusUpdatedMail;
use App\Mail\DocumentRequestSubmittedMail;
use App\Models\DocumentRequest;
use App\Services\SmsService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DocumentRequestNotifier
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function notifySubmission(DocumentRequest $request): void
    {
        if ($request->email) {
            Mail::to($request->email)->queue(new DocumentRequestSubmittedMail($request));
        }

        $template = config('services.sms.templates.document_request_submitted');
        $message = $this->replacePlaceholders($template, $request);

        if ($request->phone) {
            $this->smsService->queue($request->phone, $message, 'document_request', $request->id);
        }
    }

    public function notifyStatus(DocumentRequest $request): void
    {
        if ($request->email) {
            Mail::to($request->email)->queue(new DocumentRequestStatusUpdatedMail($request));
        }

        $template = config('services.sms.templates.document_request_status');
        $message = $this->replacePlaceholders($template, $request);

        if ($request->phone) {
            $this->smsService->queue($request->phone, $message, 'document_request', $request->id);
        }
    }

    private function replacePlaceholders(string $template, DocumentRequest $request): string
    {
        return str_replace([
            '{reference_code}',
            '{status}',
        ], [
            $request->reference_code,
            Str::title(str_replace('_', ' ', $request->status)),
        ], $template);
    }
}
