<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\NewOwnerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\ShopEditController;
use App\Http\Controllers\Owner\ReservationStatusController;
// --------------------
// 公開ページ（誰でも閲覧可）
// --------------------
Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{id}', [ShopController::class, 'show'])->name('shops.show');

// 会員登録完了画面
Route::view('/thanks', 'auth.thanks')->name('thanks');

// 店舗予約完了画面
Route::get('/done/{shop_id}', function ($shop_id) {
    return view('shop.done', compact('shop_id'));
})->name('done');

// テストメニュー（不要なら削除可）
Route::view('/menu1', 'menu1')->middleware('auth')->name('menu1');
Route::view('/menu2', 'menu2')->name('menu2');


Route::middleware('auth','verified')->group(function () {
    Route::post('/favorites/{shop}', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');
    Route::get('/reservations/{id}/edit', [ReservationController::class, 'edit'])
        ->name('reservations.edit');
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])
        ->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/reservations/{reservation}/review', [ReviewController::class, 'create'])
        ->name('reviews.create');
    Route::post('/reservations/{reservation}/review', [ReviewController::class, 'store'])
        ->name('reviews.store');
    Route::patch('/reservations/{reservation}/complete', [ReservationController::class, 'complete'])
        ->name('reservations.complete');
    Route::get('/checkout/{reservation}', [PaymentController::class, 'checkout'])
        ->name('checkout');
    Route::post('/checkout/session', [PaymentController::class, 'createCheckoutSession'])
        ->name('checkout.session');
});
// --------------------
// メール認証フロー
// --------------------
Route::middleware('auth')->group(function () {
    // 認証待ち画面
    Route::get('/email/verify', [VerificationController::class, 'notice'])
        ->name('verification.notice');

    // 認証リンククリック後の処理
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');

    // 認証メール再送
    Route::post('/email/verification-notification', [VerificationController::class, 'send'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// --------------------
// ログアウト
// --------------------
Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');

// =============================
// スタッフログイン
// =============================
Route::get('/staff/login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffLoginController::class, 'login'])->name('staff.login.submit');
Route::post('/staff/logout', [StaffLoginController::class, 'logout'])->name('staff.logout');

// =============================
// 管理者ダッシュボード
// =============================
Route::middleware(['auth'])->group(function () {
    Route::get('/staff/admin', [AdminDashboardController::class, 'index'])
        ->name('staff.admin.dashboard');

    Route::get('/staff/owner', [OwnerDashboardController::class, 'index'])
        ->name('staff.owner.dashboard');
});

// =============================
// 管理者機能
// （新規オーナー作成 / 通知送信など）
// =============================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/owners', [NewOwnerController::class, 'store'])->name('owners.store');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
});

// =============================
// オーナー機能
// （店舗登録 / 更新など）
// =============================
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/shop/{shop}/reservations', [ReservationStatusController::class, 'index'])->name('shop.reservations');

    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/create', [ShopEditController::class, 'create'])->name('create');
        Route::post('/', [ShopEditController::class, 'store'])->name('store');
        Route::get('/{shop}/edit', [ShopEditController::class, 'edit'])->name('edit');
        Route::put('/{shop}', [ShopEditController::class, 'update'])->name('update');
    });
});