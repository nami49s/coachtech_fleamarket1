<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/mypage.css')); ?>" >
</head>
<body data-tab="<?php echo e($tab); ?>">
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
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="profile-container">
            <?php if(isset($profile)): ?>
                <img src="<?php echo e(asset('storage/' . $profile->profile_image)); ?>" width="150" alt="プロフィール画像">
                <div class="profile-info">
                    <p class="name"><?php echo e($profile->name); ?></p>
                    <a href="<?php echo e(route('mypage.profile')); ?>" class="btn">プロフィールを編集</a>
                </div>
            <?php else: ?>
                <p></p>
            <?php endif; ?>
        </div>
        <div class="tabs">
            <a href="<?php echo e(route('mypage', ['tab' => 'selling'])); ?>" class="tab-link <?php echo e($tab === 'selling' ? 'active' : ''); ?>">出品した商品</a>
            <a href="<?php echo e(route('mypage', ['tab' => 'purchased'])); ?>" class="tab-link tab-link-right" id="purchased-tab">購入した商品</a>
        </div>
        <div class="tab-content">
            <?php if($tab === 'selling'): ?>
                <?php if($sellingItems->isEmpty()): ?>
                    <p>出品した商品はありません。</p>
                <?php else: ?>
                    <?php $__currentLoopData = $sellingItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item-card">
                            <a href="<?php echo e(route('item.detail', ['item' => $item->id])); ?>">
                                <img src="<?php echo e(asset('storage/' . $item->item_image)); ?>" width="100" alt="<?php echo e($item->name); ?>">
                                <p class="item-name"><?php echo e($item->name); ?></p>
                                <?php if($item->is_sold): ?>
                                    <span class="sold-label">SOLD</span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php elseif($tab === 'purchased'): ?>
                <?php if($purchasedItems->isEmpty()): ?>
                    <p>購入した商品はありません。</p>
                <?php else: ?>
                    <?php $__currentLoopData = $purchasedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item-card">
                            <a href="<?php echo e(route('item.detail', ['item' => $item->id])); ?>">
                                <img src="<?php echo e(asset('storage/' . $item->item_image)); ?>" width="100" alt="<?php echo e($item->name); ?>">
                                <p class="item-name"><?php echo e($item->name); ?></p>
                                <?php if($item->is_sold): ?>
                                    <span class="sold-label">SOLD</span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/mypage.blade.php ENDPATH**/ ?>