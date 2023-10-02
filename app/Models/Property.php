<?php

namespace App\Models;

use App\Services\Notifications\NotificationConditionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::updating(function ($property) {
            // Handle price change notification
            $originalPrice = $property->getOriginal('price');
            if ($originalPrice !== $property->price) {
                app(NotificationConditionService::class)
                    ->sendPriceChangeNotification($property);
            }

            // Handle matching characteristics notification
            $originalNeighborhoodId = $property->getOriginal('neighborhood_id');
            $originalCategoryId = $property->getOriginal('category_id');
            $originalBedrooms = $property->getOriginal('bedrooms');

            if ($originalNeighborhoodId !== $property->neighborhood_id ||
                $originalCategoryId !== $property->category_id ||
                $originalBedrooms !== $property->bedrooms
            ) {
                $matchingProperties = $property->findMatchingProperties();
                $percentageChange = ($property->price - $originalPrice) / $originalPrice * 100;

                if ($matchingProperties->isNotEmpty() || abs($percentageChange) >= 25) {
                    app(NotificationConditionService::class)
                        ->sendMatchingCharacteristicsNotification($property, $matchingProperties);
                }
            }
        });
    }

    public function findMatchingProperties()
    {
        $minPrice = $this->price * 0.75;
        $maxPrice = $this->price * 1.25;

        return self::query()
            ->where('id', '<>', $this->id)
            ->where('neighborhood_id', $this->neighborhood_id)
            ->where('property_category_id', $this->property_category_id)
            ->where('bedrooms', '>=', $this->bedrooms)
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->get();
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PropertyCategory::class);
    }

    public function followers(): MorphMany
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }
}
