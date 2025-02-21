<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            Log::debug('ログイン成功！', ['user' => Auth::user()]);
            return redirect()->route('top');
        }

        Log::debug('ログイン失敗！');
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // ログアウト処理

        // セッションを無効化し、CSRFトークンを再生成
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // ログイン画面へリダイレクト
    }
}
