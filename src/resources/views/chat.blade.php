<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>chat</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}" >
</head>
<body>
    <header class="header">
        <a class="img" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
        </a>
    </header>
    <main>
        <div class="chat-container">
            {{-- 左カラム：サイドバー --}}
            <aside class="chat-sidebar">
                <h3>その他の取引</h3>
                @if ($tradingItems->isEmpty())
                    <p>他に取引中の商品はありません。</p>
                @else
                    <ul>
                        @foreach ($tradingItems as $tradingItem)
                            <li>
                                <a href="{{ route('chat.show', $tradingItem->id) }}">
                                    {{ $tradingItem->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </aside>

        <div class="chat-main">
            <div class="{{ $isSeller ? 'seller-info' : 'buyer-info' }}">
            <div class="{{ $isSeller ? 'seller-profile-image' : 'buyer-profile-image' }}">
                @php
                    $profile = $isSeller
                        ? ($item->buyer->profile ?? null)
                        : ($item->user->profile ?? null);
                @endphp

                @if ($profile && $profile->profile_image)
                    <img src="{{ asset('storage/' . $profile->profile_image) }}" alt="相手の画像">
                @else
                    <img src="{{ asset('images/default-user.png') }}" alt="デフォルト画像">
                @endif
            </div>

            <div class="{{ $isSeller ? 'seller-name' : 'buyer-name' }}">
                <p>{{ $profile->name ?? '未設定' }}さんとの取引画面</p>
            </div>


            @if(auth()->id() === $item->buyer_id && !$item->ratingFrom(auth()->id()))
            <!-- 取引完了ボタンを押すと評価モーダル -->
            <button class="btn-complete"
 id="completeBtn">取引を完了する</button>

            <div id="ratingModalBuyer" class="modal hidden">
                <p class="modaltitle">取引が完了しました。</p>
                <p class="comment">今回の取引相手はどうでしたか？</p>
                <form action="{{ route('ratings.store', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="ratee_id" value="{{ $item->user_id }}">
                    <label></label>
                    <div class="star-rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                            <label for="star{{ $i }}" title="{{ $i }}つ星">★</label>
                        @endfor
                    </div>
                    <hr class="modal-divider">
                    <div class="submit-area">
                        <button type="submit" class="rating-submit">送信する</button>
                    </div>
                </form>
            </div>

            <script>
                document.getElementById('completeBtn').addEventListener('click', () => {
                    document.getElementById('ratingModalBuyer').classList.remove('hidden');
                });
            </script>
        @endif

        @if(auth()->id() === $item->user_id && $item->ratingFrom($item->buyer_id) && !$item->ratingFrom(auth()->id()))
            <!-- 出品者への評価モーダル自動表示 -->
            <div id="ratingModalSeller" class="modal">
                <p class="modaltitle">取引が完了しました。</p>
                <p class="comment">今回の取引相手はどうでしたか？</p>
                <form action="{{ route('ratings.store', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="ratee_id" value="{{ $item->buyer_id }}">
                    <label></label>
                    <div class="star-rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                            <label for="star{{ $i }}" title="{{ $i }}つ星">★</label>
                        @endfor
                    </div>
                    <hr class="modal-divider">
                    <div class="submit-area">
                        <button type="submit" class="rating-submit">送信する</button>
                    </div>
                </form>
            </div>
            <script>
                // ページ読み込み時にモーダル表示
                window.onload = () => {
                    document.getElementById('ratingModalSeller').classList.remove('hidden');
                };
            </script>
        @endif
        </div>

        <div class="purchase-info">
            <div class="item-image">
                @if ($item->item_image)
                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}" width="200">
                @else
                    <p>画像なし</p>
                @endif
            </div>

            <div class="item-info">
                <h2>{{ $item->name }}</h2>
                <p>
                    <span class="price-symbol">¥</span>
                    <span class="price-value">{{ number_format($item->price) }}</span>
                </p>
            </div>
        </div>
        <div class="chat-box">
            <div class="chat-messages">
            @foreach ($messages as $message)
                @php
                    $isOwn = $message->user_id === auth()->id();
                    $profile = $message->user->profile ?? null;
                @endphp

                <div class="message-wrapper {{ $isOwn ? 'right' : 'left' }}">
                    {{-- プロフィール画像と名前（外に出す） --}}
                    <div class="message-user-info">
                        @if ($isOwn)
                            {{-- 自分のメッセージ：名前 → 画像 --}}
                            <span class="chat-username">{{ $profile->name ?? '未設定' }}</span>
                            @if ($profile && $profile->profile_image)
                                <img src="{{ asset('storage/' . $profile->profile_image) }}" alt="プロフィール画像" class="chat-profile-image"style="margin-left: 8px; margin-right: 0;">
                            @else
                                <img src="{{ asset('images/default-user.png') }}" alt="デフォルト画像" class="chat-profile-image">
                            @endif
                        @else
                            {{-- 相手のメッセージ：画像 → 名前 --}}
                            @if ($profile && $profile->profile_image)
                                <img src="{{ asset('storage/' . $profile->profile_image) }}" alt="プロフィール画像" class="chat-profile-image">
                            @else
                                <img src="{{ asset('images/default-user.png') }}" alt="デフォルト画像" class="chat-profile-image">
                            @endif
                            <span class="chat-username">{{ $profile->name ?? '未設定' }}</span>
                        @endif
                    </div>

                    {{-- メッセージ本文（背景付き） --}}
                    <div class="message {{ $isOwn ? 'right' : 'left' }}">
                        <p>{{ $message->message }}</p>
                        @if ($message->image_path)
                            <img src="{{ asset('storage/' . $message->image_path) }}" alt="画像" style="max-width: 200px;">
                        @endif
                    </div>

                    {{-- 操作ボタン --}}
                    @if ($isOwn)
                        <div class="message-actions">
                            <form action="{{ route('chat.destroy', $message->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">削除</button>
                            </form>
                            <a href="javascript:void(0);" 
                                class="edit-button" 
                                data-id="{{ $message->id }}" 
                                data-message="{{ $message->message }}">
                                編集
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- メッセージ送信フォーム --}}
            <form action="{{ route('chat.store', $item->id) }}" method="POST" class="chat-form" enctype="multipart/form-data" id="chat-form">
                @csrf
                <textarea id="message" name="message" rows="2" placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
                {{-- 編集時用 --}}
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="edit_message_id" id="edit-message-id" value="">

                {{-- 画像添付 --}}
                <label for="image-upload" class="btn btn-outline-secondary">画像を追加</label>
                <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">
                <span id="file-name" style="margin-left: 10px; font-size: 0.9em; color: #555;"></span>
                <div id="image-preview" style="margin-top: 10px;"></div>
                <button type="submit" class="chat-submit-btn">
                    <img src="{{ asset('images/e99395e98ea663a8400f40e836a71b8c4e773b01.jpg') }}" alt="送信">
                </button>
            </form>
        </div>
    </div>
</div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('ratingModal');
        const closeBtn = document.getElementById('closeModal');

        @if(session('show_rating_modal'))
            modal.style.display = 'block';
        @endif

        closeBtn?.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        const textarea = document.getElementById('message');
        const storageKey = 'chat_draft_message_{{ $item->id }}';
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
</html>