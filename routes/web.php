<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\DriverManagementController;
use App\Http\Controllers\MitraLaundryController;
use App\Http\Controllers\WablasWebhookController;
use App\Http\Controllers\BundlePurchaseController;

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

// ================= PUBLIC =================
Route::get('/bundle', [BundlePurchaseController::class, 'index'])->name('bundle.index');
Route::post('/bundle/beli', [BundlePurchaseController::class, 'store'])->name('bundle.store');
Route::post('/bundle/check-active', [BundlePurchaseController::class, 'checkActive'])->name('bundle.checkActive');


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

Route::post('/buat-pesanan/draft', [OrderController::class, 'storeDraft'])
    ->name('buat-pesanan.draft');

/*
|--------------------------------------------------------------------------
| AUTH ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth.admin')
    ->name('logout');

// NOTE: dua route ini sebaiknya sebenarnya juga masuk grup auth.admin di
// bawah (destroy & export data pesanan seharusnya butuh login admin).
// Dibiarkan posisinya sesuai aslinya, tapi ditandai untuk direview.
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])
    ->name('admin.orders.destroy');

Route::get('/orders/export', [OrderController::class, 'exportCsv'])
    ->name('admin.orders.export');

/*
|--------------------------------------------------------------------------
| ADMIN (PROTECTED) — semua route admin, termasuk upload foto,
| sekarang konsisten di dalam middleware auth.admin
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

    Route::post('/orders/{id}/nullify-driver', [OrderController::class, 'nullifyDriver'])
        ->name('admin.orders.nullifyDriver');

    // ✅ FIX: upload/hapus foto admin sekarang pakai method khusus admin di
    // OrderController (bukan DriverController::uploadBuktiPengambilan dkk),
    // supaya tidak kena guard session('driver_id') yang selalu null untuk
    // admin. Sekaligus sekarang terlindungi middleware auth.admin.
    Route::post('/pesanan/{id}/foto/pengambilan', [OrderController::class, 'uploadFotoPengambilan'])
        ->name('admin.foto.pengambilan');
    Route::post('/pesanan/{id}/foto/nota', [OrderController::class, 'uploadFotoNota'])
        ->name('admin.foto.nota');
    Route::post('/pesanan/{id}/foto/pengiriman', [OrderController::class, 'uploadFotoPengiriman'])
        ->name('admin.foto.pengiriman');
    Route::delete('/foto/{photoId}', [OrderController::class, 'deleteFotoAdmin'])
        ->name('admin.foto.delete');

    Route::get('/verifikasi-profile', [CustomerProfileController::class, 'index'])
        ->name('admin.verifikasi-profile');

    Route::post('/verifikasi-profile/{profile}', [CustomerProfileController::class, 'updateStatus'])
        ->name('admin.verifikasi-profile.update');

        // ---- Bundle (admin) ----
    Route::prefix('bundle-purchases')->name('admin.bundlePurchases.')->group(function () {
        Route::get('/', [BundlePurchaseController::class, 'adminIndex'])->name('index');
        Route::post('/{id}/approve', [BundlePurchaseController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [BundlePurchaseController::class, 'reject'])->name('reject');
    });

    // ---- Promosi (admin) ----
    Route::get('/promosi', [PromotionController::class, 'adminIndex'])->name('admin.promosi.index');
    Route::get('/promosi/create', [PromotionController::class, 'create'])->name('admin.promosi.create');
    Route::post('/promosi', [PromotionController::class, 'store'])->name('admin.promosi.store');
    Route::get('/promosi/{promosi}/edit', [PromotionController::class, 'edit'])->name('admin.promosi.edit');
    Route::put('/promosi/{promosi}', [PromotionController::class, 'update'])->name('admin.promosi.update');
    Route::delete('/promosi/{promosi}', [PromotionController::class, 'destroy'])->name('admin.promosi.destroy');

    // ---- Manajemen Driver (admin) ----
    Route::prefix('drivers')->name('admin.drivers.')->group(function () {
        Route::get('/', [DriverManagementController::class, 'index'])->name('index');
        Route::post('/', [DriverManagementController::class, 'store'])->name('store');
        Route::get('/{id}', [DriverManagementController::class, 'show'])->name('show');
        Route::post('/{id}/toggle-active', [DriverManagementController::class, 'toggleActive'])->name('toggleActive');
        Route::post('/{id}/reset-password', [DriverManagementController::class, 'resetPassword'])->name('resetPassword');
        Route::post('/{id}/document', [DriverManagementController::class, 'uploadDocument'])->name('document.upload');
        Route::delete('/{id}/document', [DriverManagementController::class, 'deleteDocument'])->name('document.delete');
    });

    // ---- Manajemen Mitra Laundry (admin) ----
    Route::prefix('mitra')->name('admin.mitra.')->group(function () {
        Route::get('/', [MitraLaundryController::class, 'index'])->name('index');
        Route::post('/', [MitraLaundryController::class, 'store'])->name('store');
        Route::put('/{id}', [MitraLaundryController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-status', [MitraLaundryController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('/{id}', [MitraLaundryController::class, 'show'])->name('show');
    });
});

/*
|--------------------------------------------------------------------------
| DRIVER AUTH
|--------------------------------------------------------------------------
*/

Route::get('/driver/login', [AuthController::class, 'showDriverLogin'])->name('driver.login');
Route::post('/driver/login', [AuthController::class, 'driverLogin'])->name('driver.login.post');
Route::post('/driver/logout', [AuthController::class, 'driverLogout'])->name('driver.logout');

/*
|--------------------------------------------------------------------------
| DRIVER DASHBOARD (PROTECTED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/ambil/{id}', [DriverController::class, 'ambilPesanan'])->name('ambil');
    Route::post('/update-status/{id}', [DriverController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/lepas/{id}', [DriverController::class, 'lepasPesanan'])->name('lepas');

    Route::get('/pesanan/{id}', [DriverController::class, 'detail'])->name('pesanan.detail');
    Route::post('/pesanan/{id}/update', [DriverController::class, 'updateByDriver'])->name('pesanan.update');

    // ✅ Foto milik driver dipindah ke dalam grup auth.driver juga,
    // supaya konsisten terproteksi (sebelumnya berada di luar middleware).
    Route::post('/pesanan/{id}/foto/pengambilan', [DriverController::class, 'uploadBuktiPengambilan'])->name('foto.pengambilan');
    Route::post('/pesanan/{id}/foto/nota', [DriverController::class, 'uploadBuktiNota'])->name('foto.nota');
    Route::post('/pesanan/{id}/foto/pengiriman', [DriverController::class, 'uploadBuktiPengiriman'])->name('foto.pengiriman');
    Route::delete('/foto/{photoId}', [DriverController::class, 'deleteFoto'])->name('foto.delete');
});

/*
|--------------------------------------------------------------------------
| PROMOSI (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/promosi', [PromotionController::class, 'index'])
    ->name('promosi.index');

/*
|--------------------------------------------------------------------------
| WEBHOOK
|--------------------------------------------------------------------------
*/

Route::post('/webhook/wablas/inbound', [WablasWebhookController::class, 'handle']);

