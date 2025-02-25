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
    public function update(AddressRequest $addressRequest, ProfileRequest $profileRequest )
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        $profile = auth()->user()->profile ?? new \App\Models\Profile();
        $profile->user_id = auth()->id();

        if ($profileRequest->hasFile('profile_image')) {
            $path = $profileRequest->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = $path;
        }
        $profile->name = $addressRequest->input('name');
        $profile->postal_code = $addressRequest->input('postal_code');
        $profile->address = $addressRequest->input('address');
        $profile->building = $addressRequest->input('building');

        $profile->save();

        return redirect()->route('mypage')->with('success', 'プロフィールが更新されました');
    }
}
