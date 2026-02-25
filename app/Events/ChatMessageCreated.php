<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageCreated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public ChatMessage $message)
    {
        $this->message->loadMissing('session');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chat.session.' . $this->message->chat_session_id),
            new Channel('chat.waiting'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'session_id' => $this->message->chat_session_id,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->message,
            'sent_at' => $this->message->sent_at?->toIso8601String(),
            'visitor' => [
                'name' => $this->message->session->visitor_name,
                'phone' => $this->message->session->visitor_phone,
                'email' => $this->message->session->visitor_email,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }
}
