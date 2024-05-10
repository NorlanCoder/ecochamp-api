<?php

use Illuminate\Support\Facades\Broadcast;

// use App\Models\Chat;
// use App\Models\User;

// Broadcast::channel(
//     'chats.{chat}',
//     fn (User $user, Chat $chat): bool => $chat->isMember($user)
// );

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
