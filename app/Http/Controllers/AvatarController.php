<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use App\Models\AvatarSend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function sendAvatar(Request $request)
    {
        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);
        $avatar = Avatar::findOrFail($request->avatar_id);

        if (!$sender->avatars->contains($avatar)) {
            return redirect()->back()->with('error', 'You do not own this avatar.');
        }

        $sender->avatars()->detach($avatar);

        $receiver->avatars()->attach($avatar);

        if ($sender->avatars->isEmpty()) {
            $sender->image = null;
            $sender->save();
        }

        return redirect()->route('profile.view')->with('success', 'Avatar sent successfully.');
    }


    public function showOff()
    {
        $avatarSubmissions = AvatarSend::with('avatar', 'sender', 'receiver')
            ->latest()
            ->get();

        return view('page.showOff', compact('avatarSubmissions'));
    }
}
