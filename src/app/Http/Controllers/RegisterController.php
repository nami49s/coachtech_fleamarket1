<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        \Log::info('Register Request:', $request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            Log::info('User Created:', ['user' => $user]);
            auth()->login($user);

            return redirect()->route('mypage.profile')->with('success', '登録が完了しました');
        } else {
            Log::error('Failed to create user');
            return back()->with('error', 'ユーザーの作成に失敗しました');
        }
    }
}
