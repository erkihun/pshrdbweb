<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.session.{sessionId}', function ($sessionId) {
    return true;
});

Broadcast::channel('chat.waiting', function ($user) {
    return $user ? $user->can('manage chat') : false;
});

Broadcast::channel('chat.agents', function ($user) {
    if (! $user) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
