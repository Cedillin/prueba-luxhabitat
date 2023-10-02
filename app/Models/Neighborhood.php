<?php

namespace App\Models;

use App\Services\Notifications\NotificationConditionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Neighborhood extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updating(function ($neighborhood) {
            $newProperties = $neighborhood->findNewProperties();

            foreach ($neighborhood->properties as $property) {
                app(NotificationConditionService::class)
                    ->sendNewPropertiesInNeighborhoodNotification($property, $newProperties);
            }
        });
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function followers(): MorphMany
    {
        return $this->morphMany(Follow::class, 'followable');
    }
}
