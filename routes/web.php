<?php

use Illuminate\Support\Facades\Route;

// AUTH & HOME
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\UserLogoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;

// ADMIN (PAKAI HURUF BESAR!)
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KostController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

// USER
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ContractController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\ReportController as UserReportController;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/kamar', [HomeController::class, 'kamarContact'])->name('kamar');

/*
|--------------------------------------------------------------------------
| Order (umum)
|--------------------------------------------------------------------------
*/
Route::get('/order/create/{kost}', [OrderController::class, 'create'])->name('order.create');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');

/*
|--------------------------------------------------------------------------
| Admin auth
|--------------------------------------------------------------------------
*/
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin area (rapi & konsisten)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kost (pakai route terpisah sesuai filemu)
    Route::get('kost', [KostController::class, 'index'])->name('kost.index');
    Route::get('kost/create', [KostController::class, 'create'])->name('kost.create');
    Route::get('kost/{kost}/edit', [KostController::class, 'edit'])->name('kost.edit');
    Route::put('kost/{kost}', [KostController::class, 'update'])->name('kost.update');
    Route::post('kost/store', [KostController::class, 'store'])->name('kost.store');
    Route::delete('kost/{kost}', [KostController::class, 'destroy'])->name('kost.destroy');

    // Laporan (report) admin
    Route::resource('reports', AdminReportController::class);

    // Manajemen akun user
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('create', [AccountController::class, 'create'])->name('create');
        Route::post('store', [AccountController::class, 'store'])->name('store');
        Route::get('{user}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('{user}', [AccountController::class, 'update'])->name('update');
        Route::delete('{user}', [AccountController::class, 'destroy'])->name('destroy');
    });

    // Keuangan (SATU grup saja, tidak dobel-dobel)
    Route::prefix('financial')->name('financial.')->group(function () {
        // Daftar transaksi + ringkasan
        Route::get('/', [FinancialController::class, 'index'])->name('index');
        Route::get('/income', [FinancialController::class, 'income'])->name('income');
        Route::get('/expense', [FinancialController::class, 'expense'])->name('expense');

        // CRUD transaksi
        Route::post('/store', [FinancialController::class, 'store'])->name('store');
        Route::delete('/{financial}', [FinancialController::class, 'destroy'])->name('destroy');

        // Order pending & aksi admin
        Route::get('/pending-orders', [FinancialController::class, 'pendingOrders'])->name('pending-orders');
        Route::post('/orders/{order}/confirm', [FinancialController::class, 'confirmOrder'])->name('confirm-order');
        Route::post('/orders/{order}/reject',  [FinancialController::class, 'rejectOrder'])->name('reject-order');
    });

    /*
     * OPSIONAL: alias route lama supaya view lama tidak rusak.
     * Kalau kamu sudah memigrasikan semua view ke nama 'admin.financial.*',
     * BLOK INI BOLEH DIHAPUS.
     */
    Route::post('/orders/{order}/confirm', [FinancialController::class, 'confirmOrder'])->name('orders.confirm');
    Route::post('/orders/{order}/reject',  [FinancialController::class, 'rejectOrder'])->name('orders.reject');
});

/*
|--------------------------------------------------------------------------
| User area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/user/history',   [HistoryController::class, 'index'])->name('user.history.index');
});

// Kontrak user (butuh login saja, role bebas kalau kamu izinkan)
Route::middleware(['auth'])->group(function () {
    Route::get('/contract',           [ContractController::class, 'index'])->name('user.contract');
    Route::post('/contract/extend',   [ContractController::class, 'extend'])->name('user.contract.extend');
});

// Pesan (chat)
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

// Report user
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::resource('reports', UserReportController::class);
});

// Logout user
Route::post('/logout', [UserLogoutController::class, 'logout'])->name('user.logout');
