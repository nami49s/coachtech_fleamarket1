<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/edit_address.css') }}" >
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        <form action="{{ route('search') }}" method="GET" class="logout-form">
            <input type="text" name="query" placeholder="何をお探しですか？" value="{{ request()->get('query') }}">
            <button type="submit"></button>
        </form>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
                <button type="submit">ログアウト</button>
        </form>
        <a href="{{ route('mypage') }}" class="profile-link">マイページ</a>
        <a href="{{ route('sell') }}" class="create-listing-link">出品</a>
    </header>
    <main>
        <div class="update-container">
            <h2>住所の変更</h2>
            @if(session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif

            <div class="form-container">
            <form action="{{ route('update_address') }}" method="POST">
                @csrf

                <label for="postal_code">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" required>

                <label for="address">住所</label>
                <input type="text" name="address" id="address" value="{{ old('address', $profile->address ?? '') }}" required>

                <label for="building">建物名</label>
                <input type="text" name="building" id="building" value="{{ old('building', $profile->building ?? '') }}">

                <button type="submit" class="save-button">変更を保存</button>
            </form>
        </div>
    </main>