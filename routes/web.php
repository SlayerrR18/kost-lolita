<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// AUTH & HOME
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KostController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\BadgeController;

// USER
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ContractController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\ReportController as UserReportController;


/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/kamar', [HomeController::class, 'kamarContact'])->name('kamar');


/*
|--------------------------------------------------------------------------
| Rute Otentikasi Pengguna
|--------------------------------------------------------------------------
| Ini akan membuat rute /login, /register, /logout, dll. untuk pengguna biasa.
| Perintah ini sekarang akan berfungsi setelah Anda menjalankan perintah di terminal.
*/
Auth::routes(['verify' => true]);


/*
|--------------------------------------------------------------------------
| Otentikasi & Logout Admin
|--------------------------------------------------------------------------
| Dipindahkan ke /admin/login untuk menghindari konflik dengan login pengguna.
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});


/*
|--------------------------------------------------------------------------
| Pesan Kamar (Order)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/order/create/{kost}', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');
});


/*
|--------------------------------------------------------------------------
| Rute Grup Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges');
    Route::resource('kost', KostController::class);
    Route::resource('reports', AdminReportController::class);
    Route::resource('account', AccountController::class);

    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/', [FinancialController::class, 'index'])->name('index');
        Route::get('/income', [FinancialController::class, 'income'])->name('income');
        Route::get('/expense', [FinancialController::class, 'expense'])->name('expense');
        Route::post('/store', [FinancialController::class, 'store'])->name('store');
        Route::delete('/{financial}', [FinancialController::class, 'destroy'])->name('destroy');
        Route::get('/pending-orders', [FinancialController::class, 'pendingOrders'])->name('pending-orders');
        Route::post('/orders/{order}/confirm', [FinancialController::class, 'confirmOrder'])->name('confirm-order');
        Route::post('/orders/{order}/reject', [FinancialController::class, 'rejectOrder'])->name('reject-order');
    });
});


/*
|--------------------------------------------------------------------------
| Rute Grup Pengguna (User)
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    Route::controller(ContractController::class)->group(function() {
        Route::get('/contract', 'index')->name('contract');
        Route::put('/contract/update-info', 'updateInfo')->name('contract.update-info');
        Route::get('available-rooms', 'availableRooms')->name('contract.available-rooms');
        Route::post('/contract/extend', 'extend')->name('contract.extend');
    });

    Route::resource('reports', UserReportController::class);
});


/*
|--------------------------------------------------------------------------
| Fitur Umum (butuh login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

