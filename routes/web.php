<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
Use App\Http\Controllers\AdminAuthController;
Use App\Http\Controllers\AdminDashboardController;

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

//route user
Route::get('/pengajuan', [PeminjamanController::class, 'formPengajuan'])->name('user.form');
Route::post('/pengajuan', [PeminjamanController::class, 'simpanPengajuan'])->name('user.simpan');
// --- ROUTE ADMIN AUTH ---
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// --- ROUTE DASHBOARD ADMIN (Dilindungi Middleware) ---
Route::middleware(['auth'])->group(function () {
    // Ubah baris ini:
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/validasi', [App\Http\Controllers\AdminDashboardController::class, 'validasi'])->name('admin.validasi');
    Route::post('/admin/validasi/{id}/update', [App\Http\Controllers\AdminDashboardController::class, 'updateValidasi'])->name('admin.validasi.update');
    Route::get('/admin/validasi/{id}/detail', [App\Http\Controllers\AdminDashboardController::class, 'detail'])->name('admin.validasi.detail');
    Route::get('/admin/riwayat', [App\Http\Controllers\AdminDashboardController::class, 'riwayat'])->name('admin.riwayat');
    Route::get('/admin/upload-database', [App\Http\Controllers\AdminDashboardController::class, 'uploadDatabase'])->name('admin.upload');
    Route::post('/admin/upload-database/proses', [App\Http\Controllers\AdminDashboardController::class, 'processUpload'])->name('admin.upload.process');
    
}); 

Route::get('/migrasi-db', function () {
    try {
        // Memaksa migrasi berjalan tanpa butuh terminal
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true
        ]);
        return 'Migrasi ke Supabase SUKSES! 🚀';
    } catch (\Exception $e) {
        return 'Yah gagal: ' . $e->getMessage();
    }
});