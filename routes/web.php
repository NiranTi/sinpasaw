<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', function () {
        return view('admin.dashboard');
    });

});

Route::middleware(['auth', 'role:tenant'])->group(function () {

    Route::get('/tenant', function () {
        return view('tenant.dashboard');
    });

});

use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// HALAMAN REGISTER
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

// PROSES REGISTER
Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

// HALAMAN LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// DASHBOARD TENANT
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\KasirController;
use App\Http\Controllers\Tenant\StokController;
use App\Http\Controllers\Tenant\PengaturanController;

Route::redirect('/tenant', '/tenant/dashboard');

Route::prefix('tenant')->name('tenant.')->middleware(['auth', 'verified'])->group(function () {

    // Beranda / Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lunasi kasbon (dari beranda)
    Route::patch('/kasbon/{kasbon}/lunasi', [DashboardController::class, 'lunasiKasbon'])->name('kasbon.lunasi');

    // ── Kasir Digital ─────────────────────────────────────────────────
    Route::get('/kasir',  [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');

    // ── Manajemen Stok ────────────────────────────────────────────────
    Route::get('/stok',               [StokController::class, 'index'])  ->name('stok');
    Route::post('/stok',              [StokController::class, 'store'])  ->name('stok.store');
    Route::post('/stok/restock',      [StokController::class, 'restock'])->name('stok.restock');
    Route::delete('/stok/{barang}',   [StokController::class, 'destroy'])->name('stok.destroy');

    // ── Pengaturan Akun ───────────────────────────────────────────────
    Route::get('/pengaturan',          [PengaturanController::class, 'index'])         ->name('pengaturan');
    Route::put('/pengaturan/profil',   [PengaturanController::class, 'updateProfil'])  ->name('pengaturan.profil');
    Route::put('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
});

// DASHBOARD ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', function () {
        return view('admin.dashboard');
    });

});

use App\Http\Controllers\Auth\PasswordResetLinkController;

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

use App\Http\Controllers\Auth\NewPasswordController;

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

use App\Http\Controllers\Tenant\ExportController;

Route::get('/tenant/export/pdf', [ExportController::class, 'exportPdf'])
    ->name('tenant.export.pdf');

Route::get('/tenant/export/excel', [ExportController::class, 'exportExcel'])
    ->name('tenant.export.excel');
