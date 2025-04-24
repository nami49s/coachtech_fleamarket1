<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/detail.css')); ?>" >
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
    <main class="item-detail">
        <div class="item-image">
            <?php if($item->item_image): ?>
                <img src="<?php echo e(asset('storage/' . $item->item_image)); ?>" alt="<?php echo e($item->name); ?>" width="300">
            <?php else: ?>
                <p>画像なし</p>
            <?php endif; ?>
        </div>

        <div class="item-info">
            <h2><?php echo e($item->name); ?></h2>
            <p class="brand"><?php echo e($item->brand); ?></p>

            <p>
                <span class="price-symbol">¥</span>
                <span class="price-value"><?php echo e(number_format($item->price)); ?></span>
                <span class="price-tax">(税込)</span>
            </p>
            <div class="like-comment-container">
                <?php if(auth()->check()): ?>
                    <form action="<?php echo e(route('items.like', ['item' => $item->id])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="like-button">
                            <?php
                                $liked = $item->likes()->where('user_id', auth()->id())->exists();
                            ?>
                            <img
                                src="<?php echo e(asset('images/star.png')); ?>"
                                alt="star"
                                class="like-icon <?php echo e($liked ? 'liked' : 'not-liked'); ?>"
                            >
                            <span class="like-count"><?php echo e($item->likes()->count()); ?></span>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="like-button">
                        <img
                            src="<?php echo e(asset('images/star.png')); ?>"
                            alt="star"
                            class="like-icon not-liked"
                        >
                        <span class="like-count"><?php echo e($item->likes()->count()); ?></span>
                    </a>
                <?php endif; ?>

                <div class="comment-icon-wrapper">
                    <img src="<?php echo e(asset('images/comment.png')); ?>" alt="comment" class="comment-img">
                    <span class="comment-icon-count"><?php echo e($item->comments()->count()); ?></span>
                </div>
            </div>

            <?php if($item->is_sold): ?>
                <span class="sold-label">SOLD</span>
            <?php else: ?>
                <form action="<?php echo e(route('purchase.show', ['item' => $item->id])); ?>" method="GET">
                    <button type="submit" class="purchase-button">購入手続きへ</button>
                </form>
            <?php endif; ?>

            <h3>商品説明</h3>
            <p class="description"><?php echo e($item->description); ?></p>

            <h3>商品の情報</h3>
            <p><strong class="category">カテゴリー</strong>
                <?php $__currentLoopData = $item->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="category-content"><?php echo e($category->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </p>
            <p><strong class="condition">商品の状態</strong> <?php echo e($item->condition); ?></p>

            <h3 class="comment-count">コメント (<?php echo e($item->comments->count()); ?>)</h3>
                <?php $__currentLoopData = $item->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="comment">
                        <div class="comment-header">
                            <img src="<?php echo e(asset('storage/' . $comment->user->profile->profile_image)); ?>" alt="プロフィール画像" class="profile-image">
                            <p><span class="name"><?php echo e($comment->user->name); ?></span></span></p>
                        </div>
                        <p class="comment-view"><?php echo e($comment->comment); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(auth()->check()): ?>
                <form action="<?php echo e(route('comments.store', $item)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <p class="comment-title">商品へのコメント</p>
                    <?php if($errors->has('comment')): ?>
                        <p class="error-message"><?php echo e($errors->first('comment')); ?></p>
                    <?php endif; ?>
                    <textarea name="comment" placeholder="コメントを入力"></textarea>
                    <button class="comment-button" type="submit">コメントを送信する</button>
                </form>
            <?php else: ?>
                <p><a href="<?php echo e(route('login')); ?>">ログイン</a>するとコメントできます</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/detail.blade.php ENDPATH**/ ?>