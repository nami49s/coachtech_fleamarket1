<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}" >
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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="profile-container">
            @if(isset($profile))
                <img src="{{ asset('storage/' . $profile->profile_image) }}" width="150" alt="プロフィール画像">
                <div class="profile-info">
                    <p class="name">{{ $profile->name }}</p>
                    <a href="{{ route('mypage.profile') }}" class="btn">プロフィールを編集</a>
                </div>
            @else
                <p></p>
            @endif
        </div>
        <div class="tabs">
            <a href="" class="tab-link">出品した商品</a>
            <a href="" class="tab-link tab-link-right">購入した品</a>
        </div>
            <div class="tab-content">
            </div>
    </main>
</body>
</html>