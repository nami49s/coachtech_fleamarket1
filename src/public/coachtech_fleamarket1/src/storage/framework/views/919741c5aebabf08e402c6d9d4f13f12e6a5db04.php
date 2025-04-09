<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品ページ</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>" >
    <link rel="stylesheet" href="<?php echo e(asset('css/sell.css')); ?>" >
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
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
                <button type="submit">ログアウト</button>
        </form>
        <a href="<?php echo e(route('mypage')); ?>" class="profile-link">マイページ</a>
        <a href="<?php echo e(route('sell')); ?>" class="create-listing-link">出品</a>
    </header>
    <main>
        <div class="sell-container">
            <h2>商品の出品</h2>
            <form class="form-container" action="<?php echo e(route('sell.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="item-image-container">
                    <label for="item_image" class="file-label">商品画像</label>
                    <div id="drop-area" class="drop-area" onclick="document.getElementById('item_image').click();">
                        <input type="file" name="item_image" id="item_image" accept="image/*" hidden>
                        <?php $__errorArgs = ['item_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <img id="image-preview" src="" alt="画像プレビュー" style="display: none;">
                        <p id="select-text">画像を選択する</p>
                    </div>
                </div>
                    <h3>商品の詳細</h3>
                <div>
                    <label>カテゴリー</label>
                    <div class="category-buttons">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <input type="checkbox" name="category_ids[]" id="category_<?php echo e($category->id); ?>"
                                value="<?php echo e($category->id); ?>" class="category-checkbox"
                                <?php echo e(is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : ''); ?>>
                            <label for="category_<?php echo e($category->id); ?>" class="category-label">
                                <?php echo e($category->name); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['category_id'];
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
                    <label for="condition">商品の状態</label>
                    <select name="condition" id="condition">
                        <option value="" disabled selected>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                    <?php $__errorArgs = ['condition'];
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
                    <h3>商品名と説明</h3>
                <div>
                    <label for="name">商品名</label>
                    <input type="text" name="name" value="" />
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
                    <label for="brand">ブランド名</label>
                    <input type="text" name="brand" value="" />
                    <?php $__errorArgs = ['brand'];
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
                    <label for="description">商品の説明</label>
                    <textarea name="description" id="description" rows="5"></textarea>
                    <?php $__errorArgs = ['description'];
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
                    <label for="price">販売価格</label>
                    <input type="number" name="price" id="price" placeholder="￥" value="" />
                    <?php $__errorArgs = ['price'];
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
                <button type="submit">出品する</button>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('item_image').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function() {
                var img = document.getElementById('image-preview');
                img.src = reader.result;
                img.style.display = "block";

                document.getElementById('select-text').style.display = "none";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html><?php /**PATH /var/www/resources/views/sell.blade.php ENDPATH**/ ?>