<?php

namespace App\Providers;

use App\Contracts\Notifications\NotificationService;
use App\Services\Notifications\CompositeNotificationService;
use App\Services\Notifications\MandrillService;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationService::class, function ($app) {
            $services = [];

            $config = config('services.notification.services');

            if ($config['mandrill']['enabled']) {
                $services[] = new MandrillService($config['mandrill']['api_key']);
            }

//            if ($config['whatsapp']['enabled']) {
//                $services[] = new WhatsappService($config['whatsapp']['api_key']);
//            }

            return new CompositeNotificationService($services);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
