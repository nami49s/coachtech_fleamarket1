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
                    <div class="profile-details">
                    <p class="name"><?php echo e($profile->name); ?></p>
                    <p>
                        <?php if(!is_null($user->averageRating())): ?>
                            <span class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $user->averageRating()): ?>
                                    <span class="star filled">&#9733;</span>
                                <?php else: ?>
                                    <span class="star">&#9733;</span>
                                <?php endif; ?>
                            <?php endfor; ?>
                    </span>
                        <?php else: ?>
                        <?php endif; ?>
                    </p>
                    </div>
                    <a href="<?php echo e(route('mypage.profile')); ?>" class="btn">プロフィールを編集</a>
                </div>
            <?php else: ?>
                <p></p>
            <?php endif; ?>
        </div>
        <div class="tabs">
            <a href="<?php echo e(route('mypage', ['tab' => 'selling'])); ?>" class="tab-link <?php echo e($tab === 'selling' ? 'active' : ''); ?>">出品した商品</a>
            <a href="<?php echo e(route('mypage', ['tab' => 'purchased'])); ?>" class="tab-link tab-link-right" id="purchased-tab">購入した商品</a>
            <a href="<?php echo e(route('mypage', ['tab' => 'in_transaction'])); ?>" class="tab-link tab-link-right <?php echo e($tab === 'in_transaction' ? 'active' : ''); ?>">
                取引中の商品
                <?php if($unreadMessageCount > 0): ?>
                    <span class="badge"><?php echo e($unreadMessageCount); ?></span>
                <?php endif; ?>
            </a>
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
                <?php elseif($tab === 'in_transaction'): ?>
                    <?php if($inTransactionItems->isEmpty()): ?>
                        <p>取引中の商品はありません。</p>
                    <?php else: ?>
                        <?php $__currentLoopData = $inTransactionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item-card" style="position: relative; display: inline-block; margin: 10px;">
                            <a href="<?php echo e(route('chat.show', ['item' => $item->id])); ?>" style="display: block; position: relative;">
                                
                                <img src="<?php echo e(asset('storage/' . $item->item_image)); ?>" width="100" alt="<?php echo e($item->name); ?>" style="display: block;">

                                
                                <?php if($item->unread_count > 0): ?>
                                    <span style="
                                            position: absolute;
                                            top: 5px;
                                            left: 5px;
                                            width: 20px;
                                            height: 20px;
                                            background: red;
                                            color: white;
                                            border-radius: 50%;
                                            font-size: 12px;
                                            line-height: 20px;
                                            text-align: center;
                                        ">
                                        <?php echo e($item->unread_count); ?>

                                    </span>
                                <?php endif; ?>

                                <p class="item-name"><?php echo e($item->name); ?></p>
                                <span class="in-transaction-label">取引中</span>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php /**PATH /var/www/resources/views/mypage.blade.php ENDPATH**/ ?>