<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{id}', [ShopController::class, 'show'])->name('shops.show');

Route::view('/thanks', 'auth.thanks')->name('thanks');

Route::get('/done/{shop_id}', function ($shop_id) {
    return view('shop.done', compact('shop_id'));
})->name('done');

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

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [VerificationController::class, 'send'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');

Route::get('/staff/login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffLoginController::class, 'login'])->name('staff.login.submit');
Route::post('/staff/logout', [StaffLoginController::class, 'logout'])->name('staff.logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/staff/admin', [AdminDashboardController::class, 'index'])
        ->name('staff.admin.dashboard');

    Route::get('/staff/owner', [OwnerDashboardController::class, 'index'])
        ->name('staff.owner.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/owners', [NewOwnerController::class, 'store'])->name('owners.store');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
});

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