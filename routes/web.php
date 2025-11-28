<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing');



/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen Kamar (CRUD)
        Route::resource('rooms', RoomController::class);

        // Manajemen Order (Konfirmasi Pesanan)
        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

/*
|--------------------------------------------------------------------------
| Tenant Routes - Order Management (Independent)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Form pemesanan kamar (user pilih kamar -> create order)
    // Route ini independent, tidak perlu approved order
    Route::get('/user/rooms/{room}/order', [UserOrderController::class, 'create'])
        ->name('user.orders.create');

    // Simpan pesanan kamar
    Route::post('/user/rooms/{room}/order', [UserOrderController::class, 'store'])
        ->name('user.orders.store');

    // Daftar pesanan milik user
    Route::get('/user/orders', [UserOrderController::class, 'index'])
        ->name('user.orders.index');

    // Detail 1 pesanan milik user
    Route::get('/user/orders/{order}', [UserOrderController::class, 'show'])
        ->name('user.orders.show');
});

/*
|--------------------------------------------------------------------------
| Tenant Dashboard Route (Protected by ApprovedOrderMiddleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'approved.order'])->group(function () {
    // Dashboard Penghuni - hanya bisa diakses jika punya order yang approved
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
});

require __DIR__.'/auth.php';
