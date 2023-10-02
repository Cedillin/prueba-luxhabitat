<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationService;
use App\Notifications\PropertyNotification;

class MandrillService implements NotificationService
{
    public function send($recipient, $message): void
    {
        $recipient->notify(new PropertyNotification($message));
    }
}
