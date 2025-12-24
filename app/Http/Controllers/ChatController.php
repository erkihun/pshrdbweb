<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageCreated;
use App\Http\Requests\StoreChatMessageRequest;
use App\Http\Requests\StoreChatStartRequest;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\OfficeHoursService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function start(StoreChatStartRequest $request, OfficeHoursService $hours): JsonResponse
    {
        if (! $hours->isOpen()) {
            return response()->json([
                'status' => 'closed',
                'message' => __('common.messages.chat_closed', ['hours' => $hours->summary()]),
                'hours' => $hours->summary(),
            ], 423);
        }

        $session = ChatSession::create([
            'visitor_name' => $request->input('name'),
            'visitor_phone' => $request->input('phone'),
            'visitor_email' => $request->input('email'),
            'status' => 'waiting',
            'started_at' => now(),
        ]);

        $message = null;

        if ($request->filled('message')) {
            $message = $session->messages()->create([
                'sender_type' => 'visitor',
                'message' => strip_tags($request->input('message')),
                'sent_at' => now(),
            ]);

            event(new ChatMessageCreated($message));
        }

        return response()->json([
            'status' => 'waiting',
            'session' => $session,
            'message' => $message,
        ], 201);
    }

    public function message(StoreChatMessageRequest $request, ChatSession $chatSession): JsonResponse
    {
        if ($chatSession->status === 'closed') {
            return response()->json(['message' => __('common.messages.chat_closed_session')], 409);
        }

        $message = $chatSession->messages()->create([
            'sender_type' => 'visitor',
            'message' => strip_tags($request->input('message')),
            'sent_at' => now(),
        ]);

        event(new ChatMessageCreated($message));

        return response()->json([ 'message' => $message ]);
    }

    public function status(OfficeHoursService $hours): JsonResponse
    {
        return response()->json([
            'open' => $hours->isOpen(),
            'summary' => $hours->summary(),
        ]);
    }
}
