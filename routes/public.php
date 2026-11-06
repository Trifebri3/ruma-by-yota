<?php

use App\Http\Controllers\Public\BusinessController;
use App\Http\Controllers\Public\GroupController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (ruma.siyota.org)
|--------------------------------------------------------------------------
| Sederhana, visual, bersih, terbuka tanpa login.
| Menampilkan nama kelompok, logo, produk, lahan public, dan PIC jika disetujui.
| Tidak pernah menampilkan data keuangan, anggota pribadi, atau feedback internal.
*/

Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/kelompok-binaan/{slug}', [GroupController::class, 'show'])->name('public.groups.show');
Route::get('/usaha-binaan/{slug}', [BusinessController::class, 'show'])->name('public.businesses.show');

// Pendaftaran Kelompok Baru
Route::get('/daftar-kelompok', [RegistrationController::class, 'index'])->name('register.group');
Route::post('/daftar-kelompok', [RegistrationController::class, 'store'])->name('register.group.store');
Route::get('/pendaftaran-berhasil', [RegistrationController::class, 'success'])->name('register.success');
