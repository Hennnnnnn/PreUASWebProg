<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoinController extends Controller
{
    public function addCoin()
    {
        $user = Auth::user();
        $user->coin += 100;
        $user->save();

        return redirect('/shop');
    }
}
