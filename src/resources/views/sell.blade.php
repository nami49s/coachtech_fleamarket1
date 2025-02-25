<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品ページ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" >
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
        <div class="sell-container">
            <h2>商品の出品</h2>
            <form class="form-container" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="item-image-container">
                    <label for="item_image" class="file-label">商品画像</label>
                    <input type="file" name="item_image" id="item_image" accept="image/*" >
                </div>
                    <p>商品の詳細</p>
                <div>
                    <label for="category_id">カテゴリー</label>
                    <input type="" name="category_id" value="" />
                </div>
                <div>
                    <label for="condition">商品の状態</label>
                    <select name="condition" id="condition">
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>
                    <p>商品名と説明</p>
                <div>
                    <label for="name">商品名</label>
                    <input type="text" name="name" value="" />
                </div>
                <div>
                    <label for="brand">ブランド名</label>
                    <input type="text" name="name" value="" />
                </div>
                <div>
                    <label for="description">商品の説明</label>
                    <textarea name="description" id="description" rows="5"></textarea>
                </div>
                <div>
                    <label for="price">販売価格</label>
                    <input type="number" name="price" id="price" value="" />
                </div>
                <button type="submit">出品する</button>
</body>
</html>