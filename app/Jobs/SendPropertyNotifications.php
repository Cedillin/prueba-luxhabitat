<?php

namespace App\Jobs;

use App\Contracts\Notifications\NotificationService;
use App\Models\Follow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPropertyNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(): void
    {
        $follows = Follow::where(function ($query) {
            $query->where('notification_frequency', 'daily')
                ->where('last_notified_at', '<=', now()->subDay());
        })->orWhere(function ($query) {
            $query->where('notification_frequency', 'weekly')
                ->where('last_notified_at', '<=', now()->subWeek());
        })->get();

        foreach ($follows as $follow) {
            $user = $follow->user;

            $details = [
                // This can be translated, for now I will use english everywhere
                'body' => 'The property you are following has a new update.',
                // In a real scenario we should have slugs or some composed url for this
                'url' => url('/path-to-property'),
            ];

            $this->notificationService->send($user, $details);

            $follow->update(['last_notified_at' => now()]);
        }
    }
}
