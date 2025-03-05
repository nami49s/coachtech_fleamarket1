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
            <p class="brand">{{ $item->brand }}</p>

            <p>
                <span class="price-symbol">¥</span>
                <span class="price-value">{{ number_format($item->price) }}</span>
                <span class="price-tax">(税込)</span>
            </p>
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

            <form action="" method="GET">
                <button type="submit" class="purchase-button">購入手続きへ</button>
            </form>

            <h3>商品説明</h3>
            <p class="description">{{ $item->description }}</p>

            <h3>商品の情報</h3>
            <p><strong class="category">カテゴリー</strong><span class="category-content">{{ $item->category->name }}</span></p>
            <p><strong class="condition">商品の状態</strong> {{ $item->condition }}</p>

            <h3>コメント</h3>
                @foreach($item->comments as $comment)
                    <div class="comment">
                        <div class="comment-header">
                            <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="プロフィール画像" class="profile-image">
                            <p><span class="name">{{ $comment->user->name }}</span></span></p>
                        </div>
                        <p class="comment-view">{{ $comment->comment }}</p>
                    </div>
                @endforeach

            @if(auth()->check())
                <form action="{{ route('comments.store', $item) }}" method="POST">
                    @csrf
                    <p class="comment-title">商品へのコメント</p>
                    <textarea name="comment" required placeholder="コメントを入力"></textarea>
                    <button class="comment-button" type="submit">コメントを送信する</button>
                </form>
            @else
                <p><a href="{{ route('login') }}">ログイン</a>するとコメントできます</p>
            @endif

            <a href="{{ route('mypage') }}" class="mypage-btn">マイページ</a>
            <a href="{{ route('top') }}" class="top-btn">商品一覧</a>
        </div>
    </main>
</body>
</html>