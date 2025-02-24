<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user ? $user->profile : null;

        return view('mypage', compact('profile'));
    }
}
