<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" >
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
    </header>
    <main>
        <div class="login-container">
        <h2>ログイン</h2>
        <form class="form-container" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div>
                <label for="email">メールアドレス</label>
                <input type="text" name="email" value="" />
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="password">パスワード</label>
                <input type="password" name="password" value="" />
            </div>

            <button type="submit">ログインする</button>

            <a href="{{ route('register') }}">新規登録はこちら</a>
        </form>
</body>
</html>