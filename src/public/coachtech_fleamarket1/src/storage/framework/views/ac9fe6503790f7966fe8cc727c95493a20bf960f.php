<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/register.css')); ?>" >
</head>
<body>
    <header class="header">
        <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
    </header>
    <main>
        <div class="register-container">
            <h2>会員登録</h2>
            <form class="form-container" action="<?php echo e(route('register')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="name">ユーザー名</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" />
                    <?php $__errorArgs = ['name'];
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
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" />
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
                    <input type="password" name="password" value="<?php echo e(old('password')); ?>" />
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
                <div>
                    <label for="password_confirmation">確認用パスワード</label>
                    <input type="password" name="password_confirmation" value="<?php echo e(old('password_confirmation')); ?>" />
                    <?php $__errorArgs = ['password_confirmation'];
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

                <button type="submit">登録する</button>

                <a href="<?php echo e(route('login')); ?>">ログインはこちら</a>
            </form>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/resources/views/auth/register.blade.php ENDPATH**/ ?>