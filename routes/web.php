<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\admin\DashboardController;

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

Route::get('/', function () {
    return view('welcome');
});

// Route untuk login admin
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('auth.login');
Route::post('admin/login', [AdminLoginController::class, 'login']);

// Route untuk dashboard admin, hanya bisa diakses oleh user dengan role 'admin'
Route::middleware(['role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
});
