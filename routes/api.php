<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/follow', [FollowController::class, 'follow']);
Route::delete('/unfollow/{id}', [FollowController::class, 'unfollow']);
Route::patch('/update-notification-preferences/{id}', [FollowController::class, 'updateNotificationPreferences']);

// Properties
Route::apiResource('properties', PropertyController::class);


