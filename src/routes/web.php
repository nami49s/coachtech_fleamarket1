<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\ExhibitionController;


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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register',function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/', [TopController::class, 'index'])->name('top')->middleware('auth');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', function () {
        return view('mypage');
    })->name('mypage');

    Route::get('/mypage', [MypageController::class, 'show'])->name('mypage');
    Route::get('/mypage/profile', [UpdateController::class, 'show'])->name('mypage.profile');
    Route::post('/mypage/profile', [UpdateController::class, 'update'])->name('mypage.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/sell', [ExhibitionController::class, 'create'])->name('sell');