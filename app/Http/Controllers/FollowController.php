<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowRequest;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        $data = $request->validate([
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|in:property,neighborhood,building',
            'notification_frequency' => 'required|string|in:daily,weekly',
        ]);

        // Assuming the user is authenticated, since we don't have users in the demo
        // I will default to 1, so it works always
        $user = auth()->user() ?? User::find(1);

        $follow = Follow::create([
            'user_id' => 1,
            'followable_id' => $data['entity_id'],
            'followable_type' => $data['entity_type'] ?? 'property',
            'notification_frequency' => $data['notification_frequency'] ?? 'daily',
        ]);

        return response()->json(['message' => 'Followed successfully.', 'follow' => $follow], 201);
    }

    public function unfollow($id)
    {
        $user = auth()->user() ?? User::find(1);

        $follow = Follow::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $follow->delete();

        return response()->json(['message' => 'Unfollowed successfully'], 200);
    }


    public function updateNotificationPreferences($id, Request $request)
    {
        $data = $request->validate([
            'notification_frequency' => 'required|string|in:daily,weekly',
        ]);

        $user = auth()->user() ?? User::find(1);

        $follow = Follow::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $follow->update(['notification_frequency' => $data['notification_frequency']]);

        return response()->json(['message' => 'Notification preferences updated successfully', 'follow' => $follow], 200);
    }
}
