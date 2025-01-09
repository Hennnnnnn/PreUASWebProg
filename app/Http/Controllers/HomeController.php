<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $active = "home";

        $allUser = User::where('visibility', 'visible')->get();

        return view('page.homepage', compact('active', 'allUser'));
    }

    public function add_wishlist(Request $request)
    {
        $user = Auth::user();
        $target = $request->input('target');

        if ($user->id == $target) {
            return redirect()->route('homepage')->with('status', 'You cannot add yourself to the wishlist.');
        }

        if ($user->wishlists()->where('friend_id', $target)->exists()) {
            return redirect()->route('homepage')->with('status', 'This user is already in your wishlist.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'friend_id' => $target,
        ]);

        return redirect()->route('homepage')->with('status', 'User successfully added to your wishlist.');
    }

    public function view_wishlist()
    {
        $user = Auth::user();
        $wishlist = $user->wishlists()->get();
        return view('page.wishlist', compact('wishlist'));
    }
}
