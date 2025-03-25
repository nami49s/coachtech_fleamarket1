<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品ページ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" >
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
            <form class="form-container" action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="item-image-container">
                    <label for="item_image" class="file-label">商品画像</label>
                    <div id="drop-area" class="drop-area" onclick="document.getElementById('item_image').click();">
                        <input type="file" name="item_image" id="item_image" accept="image/*" hidden>
                        @error('item_image')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <img id="image-preview" src="" alt="画像プレビュー" style="display: none;">
                        <p id="select-text">画像を選択する</p>
                    </div>
                </div>
                    <h3>商品の詳細</h3>
                <div>
                    <label>カテゴリー</label>
                    <div class="category-buttons">
                        @foreach ($categories as $category)
                        <div>
                            <input type="checkbox" name="category_ids[]" id="category_{{ $category->id }}"
                                value="{{ $category->id }}" class="category-checkbox"
                                {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                            <label for="category_{{ $category->id }}" class="category-label">
                                {{ $category->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('category_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="condition">商品の状態</label>
                    <select name="condition" id="condition">
                        <option value="" disabled selected>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                    @error('condition')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                    <h3>商品名と説明</h3>
                <div>
                    <label for="name">商品名</label>
                    <input type="text" name="name" value="" />
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="brand">ブランド名</label>
                    <input type="text" name="brand" value="" />
                    @error('brand')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="description">商品の説明</label>
                    <textarea name="description" id="description" rows="5"></textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="price">販売価格</label>
                    <input type="number" name="price" id="price" placeholder="￥" value="" />
                    @error('price')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit">出品する</button>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('item_image').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function() {
                var img = document.getElementById('image-preview');
                img.src = reader.result;
                img.style.display = "block";

                document.getElementById('select-text').style.display = "none";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>