<?php

namespace App\Services;

use App\Notifications\WriterActivityNotification;
use Illuminate\Support\Facades\Auth;

class EmailNotificationSender implements NotificationSenderInterface
{
    public function send(string $message): void
    {
        /** @var \App\Models\User|null $writer */
        $writer = Auth::user();
        if ($writer) {
            $writer->notify(new WriterActivityNotification($message));
        }
    }
}
