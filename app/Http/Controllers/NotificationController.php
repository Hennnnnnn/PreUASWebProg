<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notification = Notification::where('user_id', 'like', $user->id)->orderBy('created_at', 'desc')->get();
        return view('page.notification', compact('notification'));
    }
}
