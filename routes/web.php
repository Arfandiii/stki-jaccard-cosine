<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('admin.auth.login');
})->name('login');

Route::get('/admin', [AdminDashboardController::class, 'index'])->name('dashboard');
Route::get('/admin/search', [AdminDashboardController::class, 'search'])->name('search');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::resource('surat-masuk', SuratMasukController::class);
});