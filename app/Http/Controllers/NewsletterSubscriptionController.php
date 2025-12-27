<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ])->validate();

        $subscriber = Subscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'locale' => $request->get('locale', app()->getLocale()),
                'is_active' => true,
                'verified_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Thank you for subscribing!',
            'subscriber' => $subscriber->only(['email', 'locale']),
        ]);
    }
}
