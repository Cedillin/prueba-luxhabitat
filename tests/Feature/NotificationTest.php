<?php

use App\Models\Follow;
use App\Models\Neighborhood;
use App\Models\Property;
use App\Models\Notification as PropertyNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Services\Notifications\NotificationConditionService;

it('sends a price change notification to the follower of a property', function () {
    Notification::fake();

    // Arrange
    $user = User::factory()->create();
    $property = Property::factory()->create();
    Follow::factory()->create([
        'user_id' => $user->id,
        'followable_id' => $property->id,
        'followable_type' => Property::class,
    ]);

    // Act
    $originalPrice = $property->price;
    $property->update(['price' => $originalPrice + 500]);

    app(NotificationConditionService::class)->sendPriceChangeNotification($property);

    // Assert
    $notification = PropertyNotification::orderBy('created_at', 'desc')->first();
    expect($notification)->toBeInstanceOf(PropertyNotification::class)
        ->and($notification->user_id)->toBe($user->id)
        ->and($notification->content)->toBe('The price of the property you are following has changed!')
        ->and($notification->type)->toBe('property_price_change')
        ->and($notification->status)->toBe('sent');
});

it('sends a matching characteristics notification to the follower of a property', function () {
    Notification::fake();

    // Arrange
    $property = Property::factory()->create();
    $user = User::factory()->create();
    Follow::factory()->create([
        'user_id' => $user->id,
        'followable_id' => $property->id,
        'followable_type' => Property::class,
    ]);

    $newNeighborhood = Neighborhood::factory()->create();

    if ($newNeighborhood->id === $property->neighborhood_id) {
        $newNeighborhood = Neighborhood::factory()->create();
    }

    // Act
    $property->update(['neighborhood_id' => $newNeighborhood->id]);

    $matchingProperties = Property::query()
        ->where('neighborhood_id', $newNeighborhood->id)
        ->where('property_category_id', $property->property_category_id)
        ->where('bedrooms', '>=', $property->bedrooms)
        ->whereBetween('price', [$property->price * 0.75, $property->price * 1.25])
        ->take(5)
        ->get();

    app(NotificationConditionService::class)->sendMatchingCharacteristicsNotification($property, $matchingProperties);

    // Assert
    $notification = PropertyNotification::orderBy('created_at', 'desc')->first();
    expect($notification)->toBeInstanceOf(PropertyNotification::class)
        ->and($notification->user_id)->toBe($user->id)
        ->and($notification->content)->toBe('New properties matching the characteristics of the property you are following are available.')
        ->and($notification->type)->toBe('matching_properties')
        ->and($notification->status)->toBe('sent');
});

it('sends a new properties in neighborhood notification to the follower of a property', function () {
    Notification::fake();

    // Arrange
    $property = Property::factory()->create();
    $user = User::factory()->create();
    Follow::factory()->create([
        'user_id' => $user->id,
        'followable_id' => $property->id,
        'followable_type' => Property::class,
    ]);

    $newProperties = Property::query()->where('neighborhood_id', $property->neighborhood_id)->take(5)->get();

    // Act
    app(NotificationConditionService::class)->sendNewPropertiesInNeighborhoodNotification($property, $newProperties);

    // Assert
    $notification = PropertyNotification::orderBy('created_at', 'desc')->first();
    expect($notification)->toBeInstanceOf(PropertyNotification::class)
        ->and($notification->user_id)->toBe($user->id)
        ->and($notification->content)->toBe('New properties are available in the neighborhood you are following.')
        ->and($notification->type)->toBe('new_properties_in_neighborhood')
        ->and($notification->status)->toBe('sent');
});

it('sends a new properties in building notification to the follower of a property', function () {
    Notification::fake();

    // Arrange
    $property = Property::factory()->create();
    $user = User::factory()->create();
    Follow::factory()->create([
        'user_id' => $user->id,
        'followable_id' => $property->id,
        'followable_type' => Property::class,
    ]);

    $newProperties = Property::query()->where('building_id', $property->building_id)->take(5)->get();

    // Act
    app(NotificationConditionService::class)->sendNewPropertiesInBuildingNotification($property, $newProperties);

    // Assert
    $notification = PropertyNotification::orderBy('created_at', 'desc')->first();
    expect($notification)->toBeInstanceOf(PropertyNotification::class)
        ->and($notification->user_id)->toBe($user->id)
        ->and($notification->content)->toBe('New properties are available in the building you are following.')
        ->and($notification->type)->toBe('new_properties_in_building')
        ->and($notification->status)->toBe('sent');
});
