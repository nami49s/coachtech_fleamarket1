<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/verify-email.css')); ?>" >
</head>
<body>
    <header class="header">
        <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
    </header>
    <main>
        <div class="container">
            <h2>メール認証が必要です</h2>
            <p>登録されたメールアドレスに認証リンクを送信しました。メール内のリンクをクリックして認証を完了してください。</p>

            <?php if(session('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary">認証メールを再送する</button>
            </form>

            <p class="mt-3">
                ログアウトして別のアカウントでログインする場合は
                <a href="<?php echo e(route('login')); ?>"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    こちら
                </a>。
            </p>

            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>