<?php $__env->startComponent('mail::message'); ?>
# 取引が完了しました！

商品[<?php echo e($item->name); ?>]の取引が完了しました！

<?php echo e($item->buyer->name); ?>さんの評価をお願いいたします！

よろしくお願いいたします！<br>
coachtech_fleamarket
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /var/www/resources/views/emails/transaction_completed.blade.php ENDPATH**/ ?>