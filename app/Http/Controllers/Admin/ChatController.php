<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatMessageCreated;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $waiting = ChatSession::with('assignedTo')->where('status', 'waiting')->orderBy('started_at')->get();
        $active = ChatSession::with('assignedTo')->where('status', 'active')->orderBy('started_at')->get();
        $closed = ChatSession::with('assignedTo')->where('status', 'closed')->orderBy('closed_at', 'desc')->limit(30)->get();

        return view('admin.chats.index', compact('waiting', 'active', 'closed'));
    }

    public function show(ChatSession $chatSession)
    {
        $chatSession->load(['messages.user', 'assignedTo']);

        $agents = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'Officer']);
        })->orderBy('name')->get();

        return view('admin.chats.show', compact('chatSession', 'agents'));
    }

    public function update(Request $request, ChatSession $chatSession)
    {
        $data = $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'in:waiting,active,closed'],
        ]);

        $status = $data['status'] ?? null;

        if ($status) {
            $chatSession->status = $status;
            if ($status === 'closed') {
                $chatSession->closed_at = now();
            }
        }

        if (isset($data['assigned_to'])) {
            $chatSession->assigned_to = $data['assigned_to'];
        }

        if ($chatSession->isDirty()) {
            $chatSession->save();
        }

        return back()->with('success', __('common.messages.chat_updated'));
    }

    public function storeMessage(Request $request, ChatSession $chatSession)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = $chatSession->messages()->create([
            'sender_type' => 'agent',
            'user_id' => Auth::id(),
            'message' => strip_tags($data['message']),
            'sent_at' => now(),
        ]);

        event(new ChatMessageCreated($message));

        return back()->with('success', __('common.messages.message_sent'));
    }
}
