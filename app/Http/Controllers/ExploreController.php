<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $hobby = $request->input('hobby');

        $query = User::where('visibility', 'visible');

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($hobby) {
            $query->where('hobby', 'LIKE', '%' . $hobby . '%');
        }

        $users = $query->get();

        // Return the view with users
        return view('page.explore', compact('users'));
    }


}
