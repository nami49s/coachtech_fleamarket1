<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}" >
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <a class="img" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        </a>
        <form action="{{ route('search') }}" method="GET" class="search-form">
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
            <div class="like-comment-container">
                @if (auth()->check())
                    <form action="{{ route('items.like', ['item' => $item->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="like-button">
                            @php
                                $liked = $item->likes()->where('user_id', auth()->id())->exists();
                            @endphp
                            <img
                                src="{{ asset('images/star.png') }}"
                                alt="star"
                                class="like-icon {{ $liked ? 'liked' : 'not-liked' }}"
                            >
                            <span class="like-count">{{ $item->likes()->count() }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="like-button">
                        <img
                            src="{{ asset('images/star.png') }}"
                            alt="star"
                            class="like-icon not-liked"
                        >
                        <span class="like-count">{{ $item->likes()->count() }}</span>
                    </a>
                @endif

                <div class="comment-icon-wrapper">
                    <img src="{{ asset('images/comment.png') }}" alt="comment" class="comment-img">
                    <span class="comment-icon-count">{{ $item->comments()->count() }}</span>
                </div>
            </div>

            @if ($item->is_sold)
                <span class="sold-label">SOLD</span>
            @else
                <form action="{{ route('purchase.show', ['item' => $item->id]) }}" method="GET">
                    <button type="submit" class="purchase-button">購入手続きへ</button>
                </form>
            @endif

            <h3>商品説明</h3>
            <p class="description">{{ $item->description }}</p>

            <h3>商品の情報</h3>
            <div class="category-container">
                <strong class="category-label">カテゴリー</strong>
                <div class="category-wrapper">
                    @foreach ($item->categories as $category)
                        <span class="category-content">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <p><strong class="condition">商品の状態</strong> {{ $item->condition }}</p>

            <h3 class="comment-count">コメント ({{ $item->comments->count() }})</h3>
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
                    @if ($errors->has('comment'))
                        <p class="error-message">{{ $errors->first('comment') }}</p>
                    @endif
                    <textarea name="comment" placeholder="コメントを入力"></textarea>
                    <button class="comment-button" type="submit">コメントを送信する</button>
                </form>
            @else
                <p><a href="{{ route('login') }}">ログイン</a>するとコメントできます</p>
            @endif
        </div>
    </main>
</body>
</html>