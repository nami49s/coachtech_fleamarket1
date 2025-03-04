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
    <main>
        <div class="tabs">
            <a href="{{ route('top', ['tab' => 'recommended']) }}" class="tab-link {{ $tab === 'recommended' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('top', ['tab' => 'mylist']) }}" class="tab-link tab-link-right {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <div class="tab-content">
            @if ($tab === 'recommended')
                @if (isset($items) && !$items->isEmpty())
                    @foreach($items as $item)
                        @if (isset($item) && isset($item->id))
                            <div class="item-card">
                                <a href="{{ route('item.detail', ['item' => $item->id]) }}">
                                    <img src="{{ asset('storage/' . $item->item_image) }}" width="100" alt="{{ $item->name }}">
                                    <p class="item-name">{{ $item->name }}</p>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>該当する商品は見つかりませんでした。</p>
                @endif
            @elseif ($tab === 'mylist')
                <p>マイリストの内容</p>
            @endif
        </div>
    </main>
</body>
</html>