<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}" >
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <header class="header">
        <a class="img" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}"
        alt="coachtech">
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
    <main class="purchase-container">
        <div class="left-content">
            <div class="purchase-info">
                <div class="item-image">
                    @if ($item->item_image)
                        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}" width="200">
                    @else
                        <p>画像なし</p>
                    @endif
                </div>

                <div class="item-info">
                    <h2>{{ $item->name }}</h2>
                    <p>
                        <span class="price-symbol">¥</span>
                        <span class="price-value">{{ number_format($item->price) }}</span>
                    </p>
                </div>
            </div>

            <div class="payment-form">
                <form method="POST" action="{{ route('purchase.payment') }}">
                    @csrf
                    <input type="hidden" name="item_id" id="item_id" value="{{ $item->id }}">
                    <div class="payment-select">
                        <label for="payment_method">支払い方法</label>
                        <select name="payment_method" id="payment_method" onchange="this.form.submit()">
                            <option value="" disabled selected>選択してください</option>
                            <option value="convenience-store" {{ session('payment_method') == 'convenience-store' ? 'selected' : '' }}>コンビニ払い</option>
                            <option value="credit-card" {{ session('payment_method') == 'credit-card' ? 'selected' : '' }}>カード支払い</option>
                        </select>
                    </div>
                </form>

                <div class="shipping-info">
                    <h3>配送先</h3>
                    <a  class="update" href="{{ route('edit_address') }}" class="edit-button">変更する</a>

                    @php
                        $postal_code = session('shipping_postal_code') ?? ($profile->postal_code ?? '');
                        $address = session('shipping_address') ?? ($profile->address ?? '');
                        $building = session('shipping_building') ?? ($profile->building ?? '');
                    @endphp
                    @if ($postal_code && $address)
                        <p class="postal-code">〒 {{ $postal_code }}</p>
                        <p class="address">{{ $address }}</p>
                        <p class="building">{{ $building }}</p>
                    @else
                        <p>配送先情報が登録されていません。</p>
                    @endif
                </div>
                <div class="confirm-container">
                    <div class="payment-summary">
                        <table>
                            <tr>
                                <td>商品代金</td>
                                <td><span>¥{{ number_format($item->price) }}</span></td>
                            </tr>
                            <tr>
                                <td>支払い方法</td>
                                <td><span>{{ session('payment_method') == 'credit-card' ? 'カード支払い' : 'コンビニ払い' }}</span></td>
                            </tr>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('checkout') }}">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input type="hidden" name="payment_method" id="payment_method_hidden" value="{{ session('payment_method', 'credit-card') }}">
                        <button type="submit" class="confirm-button">購入する</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            let paymentText = this.options[this.selectedIndex].text;
            document.getElementById('payment-method').textContent = paymentText;
        });
    </script>
</html>