<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入画面</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/edit_address.css')); ?>" >
</head>
<body>
    <header class="header">
        <a class="img" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
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
    <main>
        <div class="update-container">
            <h2>住所の変更</h2>
            <?php if(session('success')): ?>
                <p style="color: green;"><?php echo e(session('success')); ?></p>
            <?php endif; ?>

            <div class="form-container">
            <form action="<?php echo e(route('update_address')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <label for="postal_code">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" value="<?php echo e(old('postal_code', session('shipping_postal_code', $profile->postal_code ?? ''))); ?>" required>

                <label for="address">住所</label>
                <input type="text" name="address" id="address" value="<?php echo e(old('address', session('shipping_address', $profile->address ?? ''))); ?>" required>

                <label for="building">建物名</label>
                <input type="text" name="building" id="building" value="<?php echo e(old('building', session('shipping_building', $profile->building ?? ''))); ?>">

                <button type="submit" class="save-button">変更を保存</button>
            </form>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/edit_address.blade.php ENDPATH**/ ?>