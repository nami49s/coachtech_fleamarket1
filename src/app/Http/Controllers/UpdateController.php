<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class UpdateController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile ?? null;
        return view('mypage.profile', compact('profile'));
    }
    public function update(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        $profile = auth()->user()->profile ?? new \App\Models\Profile();
        $profile->user_id = auth()->id();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = $path;
        }
        $profile->name = $request->input('name');
        $profile->postal_code = $request->input('postal_code');
        $profile->address = $request->input('address');
        $profile->building = $request->input('building');

        $profile->save();

        return redirect()->route('mypage')->with('success', 'プロフィールが更新されました');
    }
}
