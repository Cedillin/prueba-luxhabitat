<?php

use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;

Route::post('/follow', [FollowController::class, 'follow']);
Route::delete('/unfollow/{id}', [FollowController::class, 'unfollow']);
Route::patch('/update-notification-preferences/{id}', [FollowController::class, 'updateNotificationPreferences']);

