<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// CONTROLLERS
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KostController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\BadgeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

// USER CONTROLLERS
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ContractController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\ReportController as UserReportController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

// Halaman utama sekarang menjadi satu-satunya rute publik utama.
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Otentikasi Pengguna (Login, Register, dll.)
// Ini akan menangani SEMUA proses login.
Auth::routes(['verify' => true]);


/*
|--------------------------------------------------------------------------
| Rute yang Membutuhkan Login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Rute Pesan Kamar (Order)
    Route::get('/order/create/{kost}', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');

    // Rute Pesan (Messaging)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Grup Rute Pengguna (User)
    Route::middleware(['approved.order'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/history',   [HistoryController::class, 'index'])->name('history.index');
        Route::resource('reports', UserReportController::class);
    });

    // Rute Kontrak (bisa diakses oleh user)
    Route::prefix('user')->name('user.')->group(function () {
        Route::controller(ContractController::class)->group(function() {
            Route::get('/contract', 'index')->name('contract');
            Route::put('/contract/update-info', 'updateInfo')->name('contract.update-info');
            Route::get('available-rooms', 'availableRooms')->name('contract.available-rooms');
            Route::post('/contract/extend', 'extend')->name('contract.extend');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Rute Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Badge
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges');

    // Kost Management
    Route::resource('kost', KostController::class)->except(['show']);

    // Laporan (report) admin
    Route::resource('reports', AdminReportController::class);

    // Manajemen akun user
    Route::resource('account', AccountController::class)->except(['show']);

    // Keuangan
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/income', [FinancialController::class, 'income'])->name('income');
        Route::get('/expense', [FinancialController::class, 'expense'])->name('expense');
        Route::post('/store', [FinancialController::class, 'store'])->name('store');
        Route::delete('/{financial}', [FinancialController::class, 'destroy'])->name('destroy');
        Route::get('/pending-orders', [FinancialController::class, 'pendingOrders'])->name('pending-orders');
        Route::post('/orders/{order}/confirm', [FinancialController::class, 'confirmOrder'])->name('confirm-order');
        Route::post('/orders/{order}/reject',  [FinancialController::class, 'rejectOrder'])->name('reject-order');
    });
});

// Fallback redirect untuk /home yang dibuat oleh Auth::routes()
Route::get('/home', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('home');
});

