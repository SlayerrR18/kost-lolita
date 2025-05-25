<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/order', function () {
    return view('order');
});

Route::get('/reports', function () {
    return view('Users.reports');
});

Route::view('/input-reports','Users.input-reports')->name('input-reports');
