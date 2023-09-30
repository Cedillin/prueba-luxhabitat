<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationService;

class CompositeNotificationService implements NotificationService
{
    protected array $services;

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function send($recipient, $message): void
    {
        foreach ($this->services as $service) {
            $service->send($recipient, $message);
        }
    }
}
