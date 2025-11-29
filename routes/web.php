<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\AdminTenantController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\ExpenseController;

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
        Route::match(['post', 'patch'], '/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        // Manajemen penghuni kost
        Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{user}/edit', [AdminTenantController::class, 'edit'])->name('tenants.edit');
        Route::patch('/tenants/{user}', [AdminTenantController::class, 'update'])->name('tenants.update');
        Route::get('/tenants/{user}', [AdminTenantController::class, 'show'])->name('tenants.show');
        Route::delete('/tenants/{user}', [AdminTenantController::class, 'destroy'])->name('tenants.destroy');

        // Manajemen Keuangan (Pendapatan)
        Route::get('/finance/income', [IncomeController::class, 'index'])->name('finance.income.index');
        Route::get('/finance/income/create', [IncomeController::class, 'create'])->name('finance.income.create');
        Route::post('/finance/income', [IncomeController::class, 'store'])->name('finance.income.store');
        Route::get('/finance/income/{income}', [IncomeController::class, 'show'])->name('finance.income.show');
        Route::get('/finance/income/{income}/edit', [IncomeController::class, 'edit'])->name('finance.income.edit');
        Route::patch('/finance/income/{income}', [IncomeController::class, 'update'])->name('finance.income.update');
        Route::delete('/finance/income/{income}', [IncomeController::class, 'destroy'])->name('finance.income.destroy');

        // Manajemen Keuangan (Pengeluaran)
        Route::get('/finance/expense', [ExpenseController::class, 'index'])->name('finance.expense.index');
        Route::get('/finance/expense/create', [ExpenseController::class, 'create'])->name('finance.expense.create');
        Route::post('/finance/expense', [ExpenseController::class, 'store'])->name('finance.expense.store');
        Route::get('/finance/expense/{expense}', [ExpenseController::class, 'show'])->name('finance.expense.show');
        Route::get('/finance/expense/{expense}/edit', [ExpenseController::class, 'edit'])->name('finance.expense.edit');
        Route::patch('/finance/expense/{expense}', [ExpenseController::class, 'update'])->name('finance.expense.update');
        Route::delete('/finance/expense/{expense}', [ExpenseController::class, 'destroy'])->name('finance.expense.destroy');
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
