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
        \Log::info('Register Request Received:', $request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            Log::info('User Created Successfully:', ['user_id' => $user->id]);

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('verification.notice')->with('success', '登録が完了しました。メールを確認してください');
        } else {
            Log::error('User Creation Failed');
            return back()->with('error', 'ユーザーの作成に失敗しました');
        }
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
