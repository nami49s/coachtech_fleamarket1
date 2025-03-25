<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [TopController::class, 'index'])->name('top');

// ユーザー登録
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// 検索
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/item/{item}', [ExhibitionController::class, 'show'])->name('item.detail');

Route::post('/purchase/payment', [PurchaseController::class, 'updatePaymentMethod'])->name('purchase.payment');
Route::post('/checkout', [StripeController::class, 'checkout'])->name('checkout');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile', [UpdateController::class, 'show'])->name('mypage.profile');
    Route::post('/mypage/profile', [UpdateController::class, 'update'])->name('mypage.update');

    // 出品関連
    Route::get('/sell', [ExhibitionController::class, 'create'])->name('sell');
    Route::post('/sell', [ExhibitionController::class, 'store'])->name('sell.store');
    Route::get('/mypage/selling-items', [ExhibitionController::class, 'sellingItems'])->name('mypage.selling-items');


    // 商品詳細
    Route::post('/items/{item}/like', [ItemController::class, 'like'])->name('items.like');
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');

    //商品購入
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::get('/edit_address', [PurchaseController::class, 'editAddress'])->name('edit_address');
    Route::post('/update_address', [PurchaseController::class, 'updateAddress'])->name('update_address');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/success/{item}', [StripeController::class, 'success'])->name('purchase.success');
});

// ログアウト
Route::post('/logout', function () {
    Auth::logout();
    session()->flush();
    return redirect('/login')->with('success', 'ログアウトしました');
})->name('logout');

require __DIR__.'/auth.php';