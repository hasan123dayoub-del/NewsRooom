<?php

namespace App\Services;

interface NotificationSenderInterface
{
    public function send(string $message): void;
}
