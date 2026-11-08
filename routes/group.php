<?php

use App\Http\Controllers\Group\AccountController;
use App\Http\Controllers\Group\CommodityController;
use App\Http\Controllers\Group\DashboardController;
use App\Http\Controllers\Group\FinanceController;
use App\Http\Controllers\Group\LandController;
use App\Http\Controllers\Group\LearningController;
use App\Http\Controllers\Group\MarketingController;
use App\Http\Controllers\Group\ProductController;
use App\Http\Controllers\Group\ProgressReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Group Admin / Kelompok Routes (Role: user, admin)
| Mobile-First Application, Pure White, 5 Bottom Nav Menus
|--------------------------------------------------------------------------
*/

Route::prefix('group')->middleware(['role:user,admin'])->name('group.')->group(function () {
    // 1. Beranda
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Belajar (Kelas, Materi, Ruangan Zoom, Dokumen SOP)
    Route::get('/belajar', [LearningController::class, 'index'])->name('learning.index');
    Route::get('/belajar/buka-dokumen/{id}', [LearningController::class, 'openDocument'])->name('learning.open_doc');

    // 3. Usaha: Lahan, Komoditas, Keuangan, Produk
    Route::get('/lahan', [LandController::class, 'index'])->name('land.index');
    Route::post('/lahan/simpan', [LandController::class, 'store'])->name('land.store');

    Route::get('/komoditas', [CommodityController::class, 'index'])->name('commodities.index');
    Route::post('/komoditas/simpan', [CommodityController::class, 'store'])->name('commodities.store');

    Route::get('/keuangan', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/keuangan/simpan', [FinanceController::class, 'store'])->name('finance.store');

    Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
    Route::post('/produk/simpan', [ProductController::class, 'store'])->name('products.store');

    // 4. Marketing (Tugas, Unggah Foto, WhatsApp Copy)
    Route::get('/marketing', [MarketingController::class, 'index'])->name('marketing.index');
    Route::post('/marketing/kumpulkan', [MarketingController::class, 'submit'])->name('marketing.submit');

    // 5. Laporan Progress Harian Kelompok
    Route::get('/progress', [ProgressReportController::class, 'index'])->name('progress.index');
    Route::post('/progress', [ProgressReportController::class, 'store'])->name('progress.store');

    // 6. Akun & Profil Kelompok
    Route::get('/akun', [AccountController::class, 'index'])->name('account.index');
    Route::post('/akun/update', [AccountController::class, 'update'])->name('account.update');
});
