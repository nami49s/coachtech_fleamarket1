<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mypage</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}" >
</head>
<body>
    <header class="header">
        <a class="img" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        </a>
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
            <h2>プロフィール設定</h2>
            <form class="form-container" action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="profile-image-container">
                    @if($profile && $profile->profile_image)
                        <img id="preview-image" src="{{ asset('storage/' . $profile->profile_image) }}" width="150">
                    @else
                        <img id="preview-image" src="{{ asset('images/default-profile.png') }}" width="150">
                    @endif
                    <label for="profile_image" class="file-label">画像を選択する</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" hidden>
                </div>

                <div>
                    <label for="name">ユーザー名</label>
                    <input type="text" name="name" value="{{ old('name', $profile->name ?? '') }}" />
                    @error('name')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="postal_code">郵便番号</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" />
                    @error('postal_code')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="address">住所</label>
                    <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" />
                    @error('address')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="building">建物名</label>
                    <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}" />
                    @error('building')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit">更新する</button>
            </form>
        </div>
    </main>
    <script>
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewImage = document.getElementById('preview-image');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };

            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>