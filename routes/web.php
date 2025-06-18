<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\UserLogoutController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\KostController;
use App\Http\Controllers\user\DashboardController as UserDashboardController;
use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\admin\FinancialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Route untuk halaman order
Route::get('/order/create/{kost}', [OrderController::class, 'create'])->name('order.create');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/confirmation', [OrderController::class, 'confirmation'])
    ->name('order.confirmation');


// Route untuk login admin
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');



// Route untuk dashboard admin, hanya bisa diakses oleh user dengan role 'admin'
Route::middleware(['role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
});

// Route untuk dashboard user, hanya bisa diakses oleh user dengan role 'user'
Route::middleware(['role:user'])->group(function () {
    Route::get('user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    // Route untuk manajemen kost
    Route::get('kost', [KostController::class, 'index'])->name('admin.kost.index');
    Route::get('kost/create', [KostController::class, 'create'])->name('admin.kost.create');
    Route::get('kost/{kost}/edit', [KostController::class, 'edit'])->name('admin.kost.edit');
    Route::put('kost/{kost}', [KostController::class, 'update'])->name('admin.kost.update');
    Route::post('kost/store', [KostController::class, 'store'])->name('admin.kost.store');
    Route::delete('kost/{kost}', [KostController::class, 'destroy'])->name('admin.kost.destroy');

    // Route untuk manajemen order
    Route::post('/orders/{order}/confirm', [FinancialController::class, 'confirmOrder'])
        ->name('admin.orders.confirm');
    Route::post('/orders/{order}/reject', [FinancialController::class, 'rejectOrder'])
        ->name('admin.orders.reject');
});

// Route untuk manajemen akun user
Route::group(['prefix' => 'admin/account', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/', [AccountController::class, 'index'])->name('admin.account.index');
    Route::get('create', [AccountController::class, 'create'])->name('admin.account.create');
    Route::post('store', [AccountController::class, 'store'])->name('admin.account.store');
    Route::get('{user}/edit', [AccountController::class, 'edit'])->name('admin.account.edit');
    Route::put('{user}', [AccountController::class, 'update'])->name('admin.account.update');
    Route::delete('{user}', [AccountController::class, 'destroy'])->name('admin.account.destroy');
});

// Route untuk menajemen keuangan
Route::group(['prefix' => 'admin/financial', 'middleware' => ['auth', 'role:admin']], function () {
    // Daftar transaksi
    Route::get('/', [FinancialController::class, 'index'])->name('admin.financial.index');
    Route::post('/store', [FinancialController::class, 'store'])->name('admin.financial.store');
    Route::delete('/{financial}', [FinancialController::class, 'destroy'])->name('admin.financial.destroy');

    // Konfirmasi pesanan
    Route::get('/pending-orders', [FinancialController::class, 'pendingOrders'])->name('admin.financial.pending-orders');
    Route::post('/orders/{order}/confirm', [FinancialController::class, 'confirmOrder'])
        ->name('admin.financial.confirm-order');
    Route::post('/orders/{order}/reject', [FinancialController::class, 'rejectOrder'])
        ->name('admin.financial.reject-order');

    // Route untuk grafik keuangan
    Route::get('/graph', [FinancialController::class, 'graph'])
        ->name('admin.financial.graph');
});

// Route untuk logout user
Route::post('/logout', [UserLogoutController::class, 'logout'])->name('user.logout');


