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
<body data-tab="{{ $tab }}">
    <header class="header">
        <a class="img" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        </a>
        <form action="{{ route('search') }}" method="GET" class="search-form">
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
            <a href="{{ route('mypage', ['tab' => 'selling']) }}" class="tab-link {{ $tab === 'selling' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage', ['tab' => 'purchased']) }}" class="tab-link tab-link-right" id="purchased-tab">購入した品</a>
        </div>
        <div class="tab-content">
            @if ($tab === 'selling')
                @if ($sellingItems->isEmpty())
                    <p>出品した商品はありません。</p>
                @else
                    @foreach($sellingItems as $item)
                        <div class="item-card">
                            <a href="{{ route('item.detail', ['item' => $item->id]) }}">
                                <img src="{{ asset('storage/' . $item->item_image) }}" width="100" alt="{{ $item->name }}">
                                <p class="item-name">{{ $item->name }}</p>
                                @if ($item->is_sold)
                                    <span class="sold-label">SOLD</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                @endif
            @elseif ($tab === 'purchased')
                @if ($purchasedItems->isEmpty())
                    <p>購入した商品はありません。</p>
                @else
                    @foreach($purchasedItems as $item)
                        <div class="item-card">
                            <a href="{{ route('item.detail', ['item' => $item->id]) }}">
                                <img src="{{ asset('storage/' . $item->item_image) }}" width="100" alt="{{ $item->name }}">
                                <p class="item-name">{{ $item->name }}</p>
                                @if ($item->is_sold)
                                    <span class="sold-label">SOLD</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </main>
</body>
</html>