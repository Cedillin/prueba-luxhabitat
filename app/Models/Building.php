<?php

namespace App\Models;

use App\Services\Notifications\NotificationConditionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updating(function ($building) {
            $newProperties = $building->findNewProperties();

            foreach ($building->properties as $property) {
                app(NotificationConditionService::class)
                    ->sendNewPropertiesInBuildingNotification($property, $newProperties);
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
