<?php

use App\Http\Controllers\Facilitator\DashboardController;
use App\Http\Controllers\Facilitator\GroupController;
use App\Http\Controllers\Facilitator\MentoringController;
use App\Http\Controllers\Facilitator\ReviewController;
use App\Http\Controllers\Facilitator\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Facilitator Routes (Role: fasil, admin)
|--------------------------------------------------------------------------
*/

Route::prefix('facilitator')->middleware(['role:fasil,admin'])->name('facilitator.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelompok Binaan
    Route::get('/kelompok', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/kelompok/{id}', [GroupController::class, 'show'])->name('groups.show');

    // Tugas Pemasaran / Konten
    Route::get('/tugas', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tugas', [TaskController::class, 'store'])->name('tasks.store');

    // Review Submissions & Evaluasi Produk QC
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/submissions/{id}/review', [ReviewController::class, 'reviewSubmission'])->name('reviews.submission');
    Route::post('/products/{id}/evaluate', [ReviewController::class, 'evaluateProduct'])->name('products.evaluate');

    // Catatan Mentoring & Kunjungan
    Route::get('/mentoring', [MentoringController::class, 'index'])->name('mentoring.index');
    Route::post('/mentoring', [MentoringController::class, 'store'])->name('mentoring.store');
});
