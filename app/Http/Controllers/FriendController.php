<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function sendRequest($friendId)
    {
        $userId = Auth::id();

        // Check if a friend request has already been sent
        if (Friend::where(['user_id' => $userId, 'friend_id' => $friendId])->exists()) {
            return redirect()->back()->with('error', 'Friend request already sent.');
        }

        // Find the friend's name
        $friend = User::find($friendId);
        if (!$friend) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Create a new friend request
        Friend::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
            'status' => 'pending',
        ]);

        // Notify the sender
        Notification::create([
            'user_id' => $userId,
            'message' => "You sent a friend request to " . $friend->name,
        ]);

        // Notify the recipient
        $sender = Auth::user();
        Notification::create([
            'user_id' => $friendId,
            'message' => "You received a friend request from " . $sender->name,
        ]);

        return redirect()->back()->with('success', 'Friend request sent.');
    }


    public function viewRequests()
    {
        $userId = Auth::id();

        $requests = Friend::where('friend_id', $userId)
            ->where('status', 'pending')
            ->get();

        return view('page.friend.request', compact('requests'));
    }

    public function acceptRequest($requestId)
    {
        $request = Friend::find($requestId);

        if ($request && $request->friend_id == Auth::id()) {
            $request->status = 'accepted';
            $request->save();

            Friend::create([
                'user_id' => $request->friend_id,
                'friend_id' => $request->user_id,
                'status' => 'accepted',
            ]);

            return redirect()->back()->with('success', 'Friend request accepted.');
        }

        return redirect()->back()->with('error', 'Invalid request.');
    }


    public function rejectRequest($requestId)
    {
        $friendRequest = Friend::find($requestId);

        if ($friendRequest && $friendRequest->friend_id == Auth::id()) {
            $friendRequest->status = 'rejected';
            $friendRequest->save();

            return redirect()->route('friends.requests')->with('success', 'Friend request rejected.');
        }

        return redirect()->route('friends.requests')->with('error', 'Invalid request.');
    }


    public function removeFriend($friendId)
    {
        $userId = Auth::id();

        Friend::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $userId);
        })->delete();

        return redirect()->back()->with('success', 'Friend removed.');
    }

    public function addFriend(Request $request)
    {
        $search = $request->query('search');
        $userId = Auth::id();

        $users = User::where('name', 'like', '%' . $search . '%')->get();

        foreach ($users as $user) {
            $user->friendship_status = Friend::where(function ($query) use ($userId, $user) {
                $query->where('user_id', $userId)->where('friend_id', $user->id);
            })->orWhere(function ($query) use ($userId, $user) {
                $query->where('user_id', $user->id)->where('friend_id', $userId);
            })->first();
        }

        return view('page.friend.browse', compact('users'));
    }



}
