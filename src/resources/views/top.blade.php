<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/top.css') }}" >
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
        @if (auth()->check())
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                    <button type="submit">ログアウト</button>
            </form>
            <a href="{{ route('mypage') }}" class="profile-link">マイページ</a>
            <a href="{{ route('sell') }}" class="create-listing-link">出品</a>
        @endif
    </header>
    <main>
        <div class="tabs">
            <a href="{{ route('top', ['tab' => 'recommended', 'query' => request()->get('query')]) }}" class="tab-link {{ $tab === 'recommended' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('top', ['tab' => 'mylist', 'query' => request()->get('query')]) }}" class="tab-link tab-link-right {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <div class="tab-content">
            @if ($tab === 'recommended')
                @if (isset($items) && !$items->isEmpty())
                    @foreach($items as $item)
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
                @else
                    <p>該当する商品は見つかりませんでした。</p>
                @endif
            @elseif ($tab === 'mylist')
                @if (Auth::check())
                    @if (isset($items) && !$items->isEmpty())
                        @foreach($items as $item)
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
                    @else
                        <p>マイリストに商品がありません。</p>
                    @endif
                @else
                    <p></p>
                @endif
            @endif
        </div>
    </main>
</body>
</html>