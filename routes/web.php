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
use App\Http\Controllers\User\ContractController;
use App\Http\Controllers\User\FinanceController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\MessageController;

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

    // User-specific profile edit (separate page for tenants)
    Route::get('/user/profile', [\App\Http\Controllers\User\UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::patch('/user/profile', [\App\Http\Controllers\User\UserProfileController::class, 'update'])->name('user.profile.update');

    // Message System (Chat)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
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

        // Manajemen Kamar Kost
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

        // Manajemen Laporan & Masukan Penghuni
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{report}', [AdminReportController::class, 'update'])->name('reports.update');
        Route::delete('/reports/{report}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
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

    // Manajemen Kontrak Penghuni
    Route::get('/user/contract', [ContractController::class, 'index'])->name('user.contract.index');
    Route::get('/user/contract/available-rooms', [ContractController::class, 'availableRooms'])->name('user.contract.available-rooms');
    Route::post('/user/contract/extend', [ContractController::class, 'extend'])->name('user.contract.extend');
    Route::post('/user/contract/update-info', [ContractController::class, 'updateInfo'])->name('user.contract.update-info');

    // Laporan Keuangan Penghuni
    Route::get('/user/finance', [FinanceController::class, 'index'])->name('user.finance.index');

    // Manajemen Laporan & Masukan
    Route::get('/user/reports', [UserReportController::class, 'index'])->name('user.reports.index');
    Route::get('/user/reports/create', [UserReportController::class, 'create'])->name('user.reports.create');
    Route::get('/user/reports/{report}', [UserReportController::class, 'show'])->name('user.reports.show');
    Route::post('/user/reports', [UserReportController::class, 'store'])->name('user.reports.store');
});

require __DIR__.'/auth.php';
