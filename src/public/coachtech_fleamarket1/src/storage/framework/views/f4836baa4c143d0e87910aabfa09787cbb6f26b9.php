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
        <a class="img" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtech">
        </a>
    </header>
    <main>
        <div class="chat-container">
            
            <aside class="chat-sidebar">
                <h3>その他の取引</h3>
                <?php if($tradingItems->isEmpty()): ?>
                    <p>他に取引中の商品はありません。</p>
                <?php else: ?>
                    <ul>
                        <?php $__currentLoopData = $tradingItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tradingItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(route('chat.show', $tradingItem->id)); ?>">
                                    <?php echo e($tradingItem->name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </aside>

        <div class="chat-main">
            <div class="<?php echo e($isSeller ? 'seller-info' : 'buyer-info'); ?>">
            <div class="<?php echo e($isSeller ? 'seller-profile-image' : 'buyer-profile-image'); ?>">
                <?php
                    $profile = $isSeller
                        ? ($item->buyer->profile ?? null)
                        : ($item->user->profile ?? null);
                ?>

                <?php if($profile && $profile->profile_image): ?>
                    <img src="<?php echo e(asset('storage/' . $profile->profile_image)); ?>" alt="相手の画像">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/default-user.png')); ?>" alt="デフォルト画像">
                <?php endif; ?>
            </div>

            <div class="<?php echo e($isSeller ? 'seller-name' : 'buyer-name'); ?>">
                <p><?php echo e($profile->name ?? '未設定'); ?>さんとの取引画面</p>
            </div>


            <?php if(auth()->id() === $item->buyer_id && !$item->ratingFrom(auth()->id())): ?>
            <!-- 取引完了ボタンを押すと評価モーダル -->
            <button id="completeBtn">取引を完了する</button>

            <div id="ratingModalBuyer" class="modal hidden">
                <form action="<?php echo e(route('ratings.store', ['item' => $item->id])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="ratee_id" value="<?php echo e($item->user_id); ?>">
                    <label>出品者を評価:</label>
                    <select name="rating">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★</option>
                        <option value="3">★★★</option>
                        <option value="2">★★</option>
                        <option value="1">★</option>
                    </select>
                    <button type="submit">送信</button>
                </form>
            </div>

            <script>
                document.getElementById('completeBtn').addEventListener('click', () => {
                    document.getElementById('ratingModalBuyer').classList.remove('hidden');
                });
            </script>
        <?php endif; ?>

        <?php if(auth()->id() === $item->user_id && $item->ratingFrom($item->buyer_id) && !$item->ratingFrom(auth()->id())): ?>
            <!-- 出品者への評価モーダル自動表示 -->
            <div id="ratingModalSeller" class="modal">
                <form action="<?php echo e(route('ratings.store', ['item' => $item->id])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="ratee_id" value="<?php echo e($item->buyer_id); ?>">
                    <label>購入者を評価:</label>
                    <select name="rating">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★</option>
                        <option value="3">★★★</option>
                        <option value="2">★★</option>
                        <option value="1">★</option>
                    </select>
                    <button type="submit">送信</button>
                </form>
            </div>
            <script>
                // ページ読み込み時にモーダル表示
                window.onload = () => {
                    document.getElementById('ratingModalSeller').classList.remove('hidden');
                };
            </script>
        <?php endif; ?>
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
        <div class="chat-box">
            <div class="chat-messages">
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $profile = $message->user->profile ?? null;
                ?>

                <div class="message <?php echo e($message->user_id === auth()->id() ? 'right' : 'left'); ?>">
                    
                    <div class="message-user-info">
                        <?php if($profile && $profile->profile_image): ?>
                            <img src="<?php echo e(asset('storage/' . $profile->profile_image)); ?>" alt="プロフィール画像" class="chat-profile-image">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/default-user.png')); ?>" alt="デフォルト画像" class="chat-profile-image">
                        <?php endif; ?>
                        <span class="chat-username"><?php echo e($profile->name ?? '未設定'); ?></span>
                    </div>

                    
                    <p><?php echo e($message->message); ?></p>

                    
                    <?php if($message->image_path): ?>
                        <img src="<?php echo e(asset('storage/' . $message->image_path)); ?>" alt="画像" style="max-width: 200px;">
                    <?php endif; ?>

                    
                    <?php if($message->user_id === auth()->id()): ?>
                        <form action="<?php echo e(route('chat.destroy', $message->id)); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit">削除</button>
                        </form>
                        <a href="javascript:void(0);" 
                            class="edit-button" 
                            data-id="<?php echo e($message->id); ?>" 
                            data-message="<?php echo e($message->message); ?>">
                            編集
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo e(route('chat.store', $item->id)); ?>" method="POST" class="chat-form" enctype="multipart/form-data" id="chat-form">
                <?php echo csrf_field(); ?>
                <textarea id="message" name="message" rows="2" placeholder="メッセージを入力..."><?php echo e(old('message')); ?></textarea>
                
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="edit_message_id" id="edit-message-id" value="">

                
                <label for="image-upload" class="btn btn-outline-secondary">画像を追加</label>
                <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">
                <span id="file-name" style="margin-left: 10px; font-size: 0.9em; color: #555;"></span>
                <div id="image-preview" style="margin-top: 10px;"></div>

                <button type="submit">送信</button>
            </form>
        </div>
    </div>
</div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('ratingModal');
        const closeBtn = document.getElementById('closeModal');

        <?php if(session('show_rating_modal')): ?>
            modal.style.display = 'block';
        <?php endif; ?>

        closeBtn?.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        const textarea = document.getElementById('message');
        const storageKey = 'chat_draft_message_<?php echo e($item->id); ?>';
        const form = document.getElementById('chat-form');
        const methodInput = document.getElementById('form-method');
        const editMessageIdInput = document.getElementById('edit-message-id');

        // 下書き復元
        if (!textarea.value) {
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                textarea.value = saved;
            }
        }

        // 入力時に下書きを保存
        textarea.addEventListener('input', () => {
            localStorage.setItem(storageKey, textarea.value);
        });

        // 送信時に下書きを削除
        form.addEventListener('submit', () => {
            localStorage.removeItem(storageKey);
        });

        // ファイル選択時のプレビュー
        const fileInput = document.getElementById('image-upload');
        const fileNameDisplay = document.getElementById('file-name');
        const imagePreview = document.getElementById('image-preview');

        fileInput.addEventListener('change', function() {
            imagePreview.innerHTML = '';
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                fileNameDisplay.textContent = file.name;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '200px';
                        img.style.marginTop = '10px';
                        imagePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                fileNameDisplay.textContent = '';
            }
        });

        // 編集ボタンをクリックしたときの処理
        const editButtons = document.querySelectorAll('.edit-button');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const messageId = this.getAttribute('data-id');
                const messageText = this.getAttribute('data-message');

                textarea.value = messageText;
                methodInput.value = 'PUT';
                editMessageIdInput.value = messageId;
                form.action = '/chat-message/' + messageId;
            });
        });
    });
    </script>
</body>
</html><?php /**PATH /var/www/resources/views/chat.blade.php ENDPATH**/ ?>