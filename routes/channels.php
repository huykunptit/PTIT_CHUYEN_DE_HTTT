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

// Staff notifications channel
Broadcast::channel('staff.notifications', function ($user) {
    return $user->role === 'staff' || $user->role === 'admin';
});

// Public channel for seat map updates (all users can see seat status)
Broadcast::channel('showtime.{showtimeId}.seats', function ($user, $showtimeId) {
    return true; // Public channel - anyone can listen
});

