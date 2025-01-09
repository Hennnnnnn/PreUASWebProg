<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('friends');

        return view('page.friend.chat', compact('user'));
    }

    public function showChat($friend_id)
    {
        $user = Auth::user();

        $messages = Chat::where(function ($query) use ($user, $friend_id) {
            $query->where('user_id', $user->id)->where('friend_id', $friend_id);
        })->orWhere(function ($query) use ($user, $friend_id) {
            $query->where('user_id', $friend_id)->where('friend_id', $user->id);
        })->orderBy('created_at')->get();

        $friend = User::find($friend_id);

        return view('page.friend.message', compact('messages', 'friend'));
    }


    public function sendMessage(Request $request, $friend_id)
    {
        $user = Auth::user();

        $request->validate([
            'chat' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileData = null;
        $fileType = null;

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileData = file_get_contents($image->getRealPath());
            $fileType = $image->getMimeType();
        }

        // Create chat entry
        Chat::create([
            'user_id' => $user->id,
            'friend_id' => $friend_id,
            'chat' => $request->chat ?? "",
            'file_data' => $fileData,
            'file_type' => $fileType,
        ]);

        // Notify the recipient
        Notification::create([
            'user_id' => $friend_id,
            'message' => $user->name . " sent you a message",
        ]);

        return redirect()->route('message', $friend_id);
    }

}
