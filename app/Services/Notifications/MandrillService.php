<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationService;

class MandrillService implements NotificationService
{
    protected string $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send($recipient, $message)
    {

    }
}
