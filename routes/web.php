<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DebugController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\SuratKeluarController;
use App\Http\Controllers\Admin\JenisSuratMasukController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\auth\AuthController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        /* ================= SEARCH ================= */
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search', [SearchController::class, 'search'])->name('search');
        /* ================= DEBUG ================= */
    Route::get('/search-debug', [DebugController::class, 'index'])->name('search.debug');
    Route::get('/search-simple', [DebugController::class, 'search'])->name('search.simple');
        /* ======================================== */
    Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [AdminDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/history', [AdminDashboardController::class, 'history'])->name('history');
    Route::resource('jenis-surat', JenisSuratMasukController::class);
    Route::get('jenis-surat/{jenisSurat}/get', [JenisSuratMasukController::class, 'get']);
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/admin/preprocess-tfidf', [SearchController::class, 'tfidf'])
    ->name('preprocess.tfidf');
});


Route::get('/preview/{path}', function ($path) {
    $path = storage_path('app/public/' . $path);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => mime_content_type($path),
        'Content-Disposition' => 'inline; filename="'.basename($path).'"'
    ]);
})->where('path', '.*');
