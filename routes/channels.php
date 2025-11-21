<?php

use Illuminate\Support\Facades\Broadcast;

// Authenticated user channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Notifications channel for authenticated users
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Admin notifications channel
Broadcast::channel('admin.notifications', function ($user) {
    return $user->role === 'admin';
});

