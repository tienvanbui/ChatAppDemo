<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private_conversation.{id}', function ($user, $id) {
    $callable = function ($query) use ($user) {
        $query->where('user_id', $user->id);
    };

    $conversation = Conversation::whereHas('participants', $callable)
        ->whereId($id)
        ->with('users:id,name,profile_photo_path')
        ->first();

    if ($conversation && $conversation->id) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return false;
});
