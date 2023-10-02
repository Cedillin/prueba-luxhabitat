<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationService;
use App\Models\Notification;
use App\Models\Property;
use Illuminate\Support\Collection;

class NotificationConditionService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function sendPriceChangeNotification(Property $property): void
    {
        $followers = $property->followers;

        foreach ($followers as $follow) {
            $this->sendNotification($follow->user_id, [
                'content' => 'The price of the property you are following has changed!',
                'property_id' => $property->id,
            ], 'property_price_change');
        }
    }

    public function sendMatchingCharacteristicsNotification(Property $property, Collection $matchingProperties): void
    {
        $followers = $property->followers;

        foreach ($followers as $follow) {
            $user = $follow->user;

            $this->sendNotification($user->id, [
                'content' => 'New properties matching the characteristics of the property you are following are available.',
                'property_id' => $property->id,
                'matching_properties' => $matchingProperties->take(5)->pluck('id')->toArray(),
            ], 'matching_properties'
            );
        }
    }

    public function sendNewPropertiesInNeighborhoodNotification(Property $property, Collection $newProperties): void
    {
        $followers = $property->followers;

        foreach ($followers as $follow) {
            $user = $follow->user;

            $this->sendNotification($user->id, [
                'content' => 'New properties are available in the neighborhood you are following.',
                'property_id' => $property->id,
                'new_properties' => $newProperties->take(5)->pluck('id')->toArray(),
            ], 'new_properties_in_neighborhood');
        }
    }

    public function sendNewPropertiesInBuildingNotification(Property $property, Collection $newProperties): void
    {
        $followers = $property->followers;

        foreach ($followers as $follow) {
            $user = $follow->user;

            $this->sendNotification($user->id, [
                'content' => 'New properties are available in the building you are following.',
                'property_id' => $property->id,
                'new_properties' => $newProperties->take(5)->pluck('id')->toArray(),
            ], 'new_properties_in_building');
        }
    }

    protected function sendNotification($user_id, $content, $type): void
    {
        try {
            $this->notificationService->send($user_id, $content);
            $this->logNotification($user_id, $content, $type, 'sent');
        } catch (\Exception $e) {
            $this->logNotification($user_id, $content, $type, 'failed');
        }
    }

    protected function logNotification($user_id, $content, $type, $status): void
    {
        Notification::create([
            'user_id' => $user_id,
            'content' => $content['content'],
            'type' => $type,
            'status' => $status,
        ]);
    }
}
