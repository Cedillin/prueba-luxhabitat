<?php

namespace App\Contracts\Notifications;

interface NotificationService
{
    public function send($recipient, $message);
}
