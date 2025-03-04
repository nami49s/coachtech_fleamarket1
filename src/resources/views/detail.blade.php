<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}" >
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        <form action="{{ route('search') }}" method="GET" class="logout-form">
            <input type="text" name="query" placeholder="何をお探しですか？" value="{{ request()->get('query') }}">
            <button type="submit"></button>
        </form>
        @if (auth()->check())
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                    <button type="submit">ログアウト</button>
            </form>
            <a href="{{ route('mypage') }}" class="profile-link">マイページ</a>
            <a href="{{ route('sell') }}" class="create-listing-link">出品</a>
        @endif
    </header>
    <main class="item-detail">
        <div class="item-image">
            @if ($item->item_image)
                <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}" width="300">
            @else
                <p>画像なし</p>
            @endif
        </div>

        <div class="item-info">
            <h2>{{ $item->name }}</h2>
            <p>{{ $item->brand }}</p>

            <p><strong>価格:</strong> ¥{{ number_format($item->price) }}(税込)</p>
            @if (auth()->check())
                <form action="{{ route('items.like', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="like-button">
                        @if ($item->likes()->where('user_id', auth()->id())->exists())
                            ★
                        @else
                            ☆
                        @endif
                        <span class="like-count">{{ $item->likes()->count() }}</span>
                    </button>
                </form>
            @else
                <p><a href="{{ route('login') }}">ログイン</a>すると「いいね」できます</p>
            @endif

            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>

            <h3>商品の情報</h3>
            <p><strong>カテゴリー</strong> {{ $item->category->name }}</p>
            <p><strong>商品の状態</strong> {{ $item->condition }}</p>

            <a href="{{ route('mypage') }}" class="mypage-btn">マイページ</a>
            <a href="{{ route('top') }}" class="top-btn">商品一覧</a>
        </div>
    </main>
</body>
</html>