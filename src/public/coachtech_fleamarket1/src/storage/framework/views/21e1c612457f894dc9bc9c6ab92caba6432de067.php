<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入画面</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/purchase.css')); ?>" >
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <header class="header">
        <a class="img" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('images/logo.svg')); ?>"
        alt="coachtech">
        </a>
        <form action="<?php echo e(route('search')); ?>" method="GET" class="search-form">
            <input type="text" name="query" placeholder="何をお探しですか？" value="<?php echo e(request()->get('query')); ?>">
            <button type="submit"></button>
        </form>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
                <button type="submit">ログアウト</button>
        </form>
        <a href="<?php echo e(route('mypage')); ?>" class="profile-link">マイページ</a>
        <a href="<?php echo e(route('sell')); ?>" class="create-listing-link">出品</a>
    </header>
    <main class="purchase-container">
        <div class="left-content">
            <div class="purchase-info">
                <div class="item-image">
                    <?php if($item->item_image): ?>
                        <img src="<?php echo e(asset('storage/' . $item->item_image)); ?>" alt="<?php echo e($item->name); ?>" width="200">
                    <?php else: ?>
                        <p>画像なし</p>
                    <?php endif; ?>
                </div>

                <div class="item-info">
                    <h2><?php echo e($item->name); ?></h2>
                    <p>
                        <span class="price-symbol">¥</span>
                        <span class="price-value"><?php echo e(number_format($item->price)); ?></span>
                    </p>
                </div>
            </div>

            <div class="payment-form">
                <form method="POST" action="<?php echo e(route('purchase.payment')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo e($item->id); ?>">
                    <div class="payment-select">
                        <label for="payment_method">支払い方法</label>
                        <select name="payment_method" id="payment_method" onchange="this.form.submit()">
                            <option value="" disabled selected>選択してください</option>
                            <option value="convenience-store" <?php echo e(session('payment_method') == 'convenience-store' ? 'selected' : ''); ?>>コンビニ払い</option>
                            <option value="credit-card" <?php echo e(session('payment_method') == 'credit-card' ? 'selected' : ''); ?>>カード支払い</option>
                        </select>
                    </div>
                </form>

                <div class="shipping-info">
                    <h3>配送先</h3>
                    <a  class="update" href="<?php echo e(route('edit_address')); ?>" class="edit-button">変更する</a>

                    <?php
                        $postal_code = session('shipping_postal_code') ?? ($profile->postal_code ?? '');
                        $address = session('shipping_address') ?? ($profile->address ?? '');
                        $building = session('shipping_building') ?? ($profile->building ?? '');
                    ?>
                    <?php if($postal_code && $address): ?>
                        <p class="postal-code">〒 <?php echo e($postal_code); ?></p>
                        <p class="address"><?php echo e($address); ?></p>
                        <p class="building"><?php echo e($building); ?></p>
                    <?php else: ?>
                        <p>配送先情報が登録されていません。</p>
                    <?php endif; ?>
                </div>
                <div class="confirm-container">
                    <div class="payment-summary">
                        <table>
                            <tr>
                                <td>商品代金</td>
                                <td><span>¥<?php echo e(number_format($item->price)); ?></span></td>
                            </tr>
                            <tr>
                                <td>支払い方法</td>
                                <td><span><?php echo e(session('payment_method') == 'credit-card' ? 'カード支払い' : 'コンビニ払い'); ?></span></td>
                            </tr>
                        </table>
                    </div>

                    <form method="POST" action="<?php echo e(route('checkout')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="item_id" value="<?php echo e($item->id); ?>">
                        <input type="hidden" name="payment_method" id="payment_method_hidden" value="<?php echo e(session('payment_method', 'credit-card')); ?>">
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
</html><?php /**PATH /var/www/resources/views/purchase.blade.php ENDPATH**/ ?>