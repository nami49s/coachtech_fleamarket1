<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>" >
</head>
<body>
    <header class="header">
        <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
    </header>
    <main>
        <div class="login-container">
        <h2>ログイン</h2>
        <form class="form-container" action="<?php echo e(route('login.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div>
                <label for="email">メールアドレス</label>
                <input type="text" name="email" value="" />
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label for="password">パスワード</label>
                <input type="password" name="password" value="" />
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit">ログインする</button>

            <a href="<?php echo e(route('register')); ?>">新規登録はこちら</a>
        </form>
</body>
</html><?php /**PATH /var/www/resources/views/auth/login.blade.php ENDPATH**/ ?>