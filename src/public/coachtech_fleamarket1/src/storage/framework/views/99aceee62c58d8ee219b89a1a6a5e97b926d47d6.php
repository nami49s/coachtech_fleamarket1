<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>chat</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/chat.css')); ?>" >
</head>
<body>
    <header class="header">
        <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
    </header>
    <main>
        <div class="seller-info">
            <div class="seller-profile-image">
                <?php if($item->user->profile && $item->user->profile->profile_image): ?>
                    <img src="<?php echo e(asset('storage/' . $item->user->profile->profile_image)); ?>" alt="出品者画像">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/default-user.png')); ?>" alt="デフォルト画像">
                <?php endif; ?>
            </div>
            <div class="seller-name">
                <p><?php echo e($item->user->profile->name ?? '未設定'); ?>さんとの取引画面</p>
            </div>

            <form action="" method="POST" class="complete-form">
                <?php echo csrf_field(); ?>
                <button type="submit">取引を完了する</button>
            </form>
        </div>

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
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/chat/buyer.blade.php ENDPATH**/ ?>