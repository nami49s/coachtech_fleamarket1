<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/top.css')); ?>" >
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
        <?php if(auth()->check()): ?>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                    <button type="submit">ログアウト</button>
            </form>
            <a href="<?php echo e(route('mypage')); ?>" class="profile-link">マイページ</a>
            <a href="<?php echo e(route('sell')); ?>" class="create-listing-link">出品</a>
        <?php endif; ?>
    </header>
    <main>
        <div class="tabs">
            <a href="<?php echo e(route('top', ['tab' => 'recommended', 'query' => request()->get('query')])); ?>" class="tab-link <?php echo e($tab === 'recommended' ? 'active' : ''); ?>">おすすめ</a>
            <a href="<?php echo e(route('top', ['tab' => 'mylist', 'query' => request()->get('query')])); ?>" class="tab-link tab-link-right <?php echo e($tab === 'mylist' ? 'active' : ''); ?>">マイリスト</a>
        </div>
        <div class="tab-content">
            <?php if($tab === 'recommended'): ?>
                <?php if(isset($items) && !$items->isEmpty()): ?>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                <?php else: ?>
                    <p>該当する商品は見つかりませんでした。</p>
                <?php endif; ?>
            <?php elseif($tab === 'mylist'): ?>
                <?php if(Auth::check()): ?>
                    <?php if(isset($items) && !$items->isEmpty()): ?>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                    <?php else: ?>
                        <p>マイリストに商品がありません。</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/top.blade.php ENDPATH**/ ?>