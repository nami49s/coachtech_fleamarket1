<?php $__env->startComponent('mail::message'); ?>
# 取引が完了しました！

商品[<?php echo e($item->name); ?>]の取引が完了しました！

<?php echo e($item->buyer->name); ?>さんの評価をお願いいたします！

<?php $__env->startComponent('mail::button', ['url' => route('mypage')]); ?>
<?php echo e($item->buyer->name); ?>さんの評価をする
<?php echo $__env->renderComponent(); ?>

よろしくお願いいたします！<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /var/www/resources/views/emails/transaction_completed.blade.php ENDPATH**/ ?>