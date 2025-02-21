<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" >
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
    </header>
    <main>
        <div class="register-container">
            <h2>会員登録</h2>
            <form class="form-container" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label for="name">ユーザー名</label>
                    <input type="text" name="name" value="{{ old('name') }}" />
                    @error('name')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" />
                    @error('email')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password">パスワード</label>
                    <input type="password" name="password" value="{{ old('password') }}" />
                    @error('password')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation">確認用パスワード</label>
                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" />
                    @error('password_confirmation')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit">登録する</button>

                <a href="{{ route('login') }}">ログインはこちら</a>
            </form>
        </div>
    </main>
</body>
</html>