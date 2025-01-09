<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $coin = $user->coin ?? 0;

        // Get all avatars that the user hasn't purchased yet
        $purchasedAvatars = $user->avatars()->pluck('avatar_id');
        $avatars = Avatar::whereNotIn('id', $purchasedAvatars)->get();

        return view('page.shop', compact('coin', 'avatars', 'user'));
    }

    public function buyAvatar(Request $request, $avatarId)
    {
        $user = Auth::user();
        $avatar = Avatar::findOrFail($avatarId);

        if ($user->coin < $avatar->price) {
            return redirect()->route('shop')->with('error', 'Not enough coins.');
        }

        $user->coin -= $avatar->price;
        $user->save();

        $user->avatars()->attach($avatar);

        return redirect()->route('profile.view')->with('success', 'Avatar purchased successfully.');
    }
}
