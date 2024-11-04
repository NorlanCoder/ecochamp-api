<?php

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chats.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('discussions.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('messages.{id}', function ($user, $id) {
    return true;
});