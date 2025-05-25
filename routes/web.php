<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;




Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth', 'is_admin');
Route::get('/user/dashboard', function () {
    return view('user.dashboard');


})->middleware('auth');

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/order', function () {
    return view('order');
});
