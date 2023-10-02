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

            $config = config('services.notification.services', []);

            foreach ($config as $serviceKey => $serviceConfig) {
                if ($serviceConfig['enabled'] && !empty($serviceConfig['api_key'])) {
                    $services[] = $this->createService($serviceKey, $serviceConfig['api_key']);
                }
            }

            return new CompositeNotificationService($services);
        });
    }

    protected function createService($serviceKey, $apiKey): MandrillService
    {
        return match ($serviceKey) {
            'mandrill' => new MandrillService($apiKey),
            // 'whatsapp' => new WhatsappService($apiKey),
            default => throw new \InvalidArgumentException("Invalid service key: $serviceKey"),
        };
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
