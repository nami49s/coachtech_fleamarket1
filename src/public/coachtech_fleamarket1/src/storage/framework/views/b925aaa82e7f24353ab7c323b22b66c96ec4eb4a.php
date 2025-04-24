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
            <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p>メール認証を完了してください。</p>

            <?php if(session('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

            <div class="btn-container">
                <a href="<?php echo e(session('verification_link', '#')); ?>" class="btn btn-primary">
                    認証はこちらから
                </a>

                <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-resend">認証メールを再送する</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>