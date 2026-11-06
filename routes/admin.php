<?php

use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProgramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin Routes (Role: admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kelompok
    Route::get('/kelompok', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/kelompok', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/kelompok/{id}/assign-fasil', [GroupController::class, 'assignFacilitator'])->name('groups.assign_fasil');

    // Approval Queue (Kelompok, Konten, Data)
    Route::get('/persetujuan', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/persetujuan/{id}/verifikasi', [ApprovalController::class, 'verify'])->name('approvals.verify');

    // Program Pendampingan
    Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/program', [ProgramController::class, 'store'])->name('programs.store');

    // Notifikasi (WhatsApp & Email Switch, Log Status, Test Send)
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/toggle', [NotificationController::class, 'toggleChannel'])->name('notifications.toggle');
    Route::post('/notifikasi/test-wa', [NotificationController::class, 'testWhatsApp'])->name('notifications.test_wa');
    Route::post('/notifikasi/test-email', [NotificationController::class, 'testEmail'])->name('notifications.test_email');
});
