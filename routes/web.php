<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/landing', function () {
    return view('landingpage');
})->name('landing');

Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard');

Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan');
Route::get('/pesanan/search', [OrderController::class, 'search'])->name('pesanan.search');

/*
|--------------------------------------------------------------------------
| BUAT PESANAN
|--------------------------------------------------------------------------
*/

Route::get('/buat-pesanan', function () {
    return view('buat_pesanan');
})->name('buat-pesanan');

Route::post('/buat-pesanan', [OrderController::class, 'store'])
    ->name('buat-pesanan.store');

/*
|--------------------------------------------------------------------------
| AUTH ADMIN
|--------------------------------------------------------------------------
*/

// halaman login (pakai adminlandingpage.blade.php)
Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('login');

// proses login + rate limit
Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

// logout (harus login dulu)
Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth.admin')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN (PROTECTED)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth.admin')->group(function () {

    Route::get('/dashboard', [OrderController::class, 'adminDashboard'])
        ->name('admin.dashboard');

    Route::get('/orders', [OrderController::class, 'adminOrders'])
        ->name('admin.orders');

    Route::get('/orders/{id}', [OrderController::class, 'adminDetail'])
        ->name('admin.orders.detail');

    Route::put('/orders/{id}', [OrderController::class, 'update'])
        ->name('admin.orders.update');
     Route::post('/orders/{id}/nullify-driver',[OrderController::class, 'nullifyDriver'])
        ->name('admin.orders.nullifyDriver');

});

// Driver Auth
Route::get('/driver/login', [AuthController::class, 'showDriverLogin'])->name('driver.login');
Route::post('/driver/login', [AuthController::class, 'driverLogin'])->name('driver.login.post');
Route::post('/driver/logout', [AuthController::class, 'driverLogout'])->name('driver.logout');

// Driver Dashboard (dilindungi middleware)
Route::middleware(['auth.driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth.driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/ambil/{id}', [DriverController::class, 'ambilPesanan'])->name('ambil');
    Route::post('/update-status/{id}', [DriverController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/lepas/{id}', [DriverController::class, 'lepasPesanan'])
    ->name('lepas');

    Route::get('/pesanan/{id}', [DriverController::class, 'detail'])->name('pesanan.detail');
    Route::post('/pesanan/{id}/update', [DriverController::class, 'updateByDriver'])->name('pesanan.update');
});

// routes/web.php
Route::post('/driver/pesanan/{id}/foto/pengambilan', [DriverController::class, 'uploadBuktiPengambilan'])->name('driver.foto.pengambilan');
Route::post('/driver/pesanan/{id}/foto/nota', [DriverController::class, 'uploadBuktiNota'])->name('driver.foto.nota');
Route::post('/driver/pesanan/{id}/foto/pengiriman', [DriverController::class, 'uploadBuktiPengiriman'])->name('driver.foto.pengiriman');
Route::delete('/driver/foto/{photoId}', [DriverController::class, 'deleteFoto'])->name('driver.foto.delete');

Route::post('/admin/pesanan/{id}/foto/pengambilan', [DriverController::class, 'uploadBuktiPengambilan'])->name('admin.foto.pengambilan');
Route::post('/admin/pesanan/{id}/foto/nota', [DriverController::class, 'uploadBuktiNota'])->name('admin.foto.nota');
Route::post('/admin/pesanan/{id}/foto/pengiriman', [DriverController::class, 'uploadBuktiPengiriman'])->name('admin.foto.pengiriman');
Route::delete('/admin/foto/{photoId}', [DriverController::class, 'deleteFoto'])->name('admin.foto.delete');