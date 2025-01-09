<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)->get();
        $receivedAvatars = $user->receivedAvatars;

        return view('page.profile.view', compact(
            'user',
            'users',
            'receivedAvatars'
        ));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('page.profile.edit', compact('user'));
    }

    public function toggleVisibility(Request $request)
    {
        $user = Auth::user();

        if ($user->coin < 10 && $user->visibility === 'visible') {
            return redirect()->back()->with('error', 'You do not have enough coins to enable invisibility.');
        }

        if ($user->visibility === 'visible') {
            $bearImage = file_get_contents(public_path('images/bear.png'));
            $user->visibility = 'invisible';
            $user->image = $bearImage;
            $user->coin -= 10;
        } else {
            $user->visibility = 'visible';
            $user->image = null;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile visibility updated successfully.');
    }


    public function showProfile()
    {
        $user = Auth::user();
        $user->load('friends');

        return view('profile', compact('user'));
    }


    public function update(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'nullable|image|max:2048',
            'gender' => 'required|in:Male,Female',
            'hobby' => 'nullable|string',
            'instagram' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'friendship_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        $user->hobby = $request->filled('hobby') ? json_encode(array_map('trim', explode(',', $request->input('hobby')))) : null;
        $user->instagram = $request->input('instagram');
        $user->phone_number = $request->input('phone_number');
        $user->friendship_reason = $request->input('friendship_reason');

        if ($request->hasFile('image')) {
            $user->image = file_get_contents($request->file('image')->getRealPath());
        }

        $user->save();

        return redirect()->route('profile.view')->with('success', value: 'Profile updated successfully!');
    }

    public function setAvatar(Request $request, $avatarId)
    {
        $user = Auth::user();
        $avatar = Avatar::findOrFail($avatarId);

        $user->image = $avatar->image;
        $user->save();

        return redirect()->route('profile.view')->with('success', 'Avatar updated successfully.');
    }

    public function restoreProfilePicture()
    {
        $user = Auth::user();

        // Check if the user has enough coins
        if ($user->coin < 5) {
            return redirect()->back()->with('error', 'You do not have enough coins to restore your profile picture.');
        }

        // Deduct 5 coins
        $user->coin -= 5;
        $user->visibility = 'visible';

        $user->image = null;
        $user->save();

        return redirect()->route('profile.view')->with('success', 'Your profile picture has been restored!');
    }


}
