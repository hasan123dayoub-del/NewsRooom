<?php

namespace App\Services;

use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Auth;

class DatabaseNotificationSender implements NotificationSenderInterface
{
    public function send(string $message): void
    {
        /** @var \App\Models\User|null $admin */
        $admin = Auth::user();
        if ($admin) {
            $admin->notify(new AdminActivityNotification($message));
        }
    }
}
