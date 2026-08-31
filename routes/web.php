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
Route::post('/pengajuan', [PeminjamanController::class, 'simpan'])->name('user.simpan');
// --- ROUTE ADMIN AUTH ---
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/validasi', [App\Http\Controllers\AdminDashboardController::class, 'validasi'])->name('admin.validasi');
    Route::post('/admin/validasi/{id}/update', [App\Http\Controllers\AdminDashboardController::class, 'updateValidasi'])->name('admin.validasi.update');
    Route::get('/admin/validasi/{id}/detail', [App\Http\Controllers\AdminDashboardController::class, 'detail'])->name('admin.validasi.detail');
    Route::get('/admin/riwayat', [App\Http\Controllers\AdminDashboardController::class, 'riwayat'])->name('admin.riwayat');
    Route::get('/admin/upload-database', [App\Http\Controllers\AdminDashboardController::class, 'uploadDatabase'])->name('admin.upload');
    Route::post('/admin/upload-database/proses', [App\Http\Controllers\AdminDashboardController::class, 'processUpload'])->name('admin.upload.process');
    // Route untuk nandain buku udah dikembalikan
    Route::post('/admin/peminjaman/{id}/kembali', [App\Http\Controllers\AdminDashboardController::class, 'tandaiDikembalikan'])->name('admin.kembali');
    Route::get('/admin/pengaturan', [AdminDashboardController::class, 'pengaturan'])->name('admin.pengaturan');
    Route::post('/admin/pengaturan', [AdminDashboardController::class, 'updatePengaturan'])->name('admin.pengaturan.update');
    Route::get('/admin/pengaturan', [AdminDashboardController::class, 'pengaturan'])->name('admin.pengaturan');
Route::post('/admin/pengaturan', [AdminDashboardController::class, 'tambahPengaturan'])->name('admin.pengaturan.store');
Route::delete('/admin/pengaturan/{id}', [AdminDashboardController::class, 'hapusPengaturan'])->name('admin.pengaturan.destroy');
}); 
