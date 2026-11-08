<?php

use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ProgressReportController;
use App\Http\Controllers\Admin\ReportLinkController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin Routes (Role: admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Manajemen Pengguna (Buat Akun, Atur Role, Kelompok, Status Aktif/Nonaktif)
    Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
    Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');
    Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/pengguna/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle_status');
    Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // 2. Manajemen Kelompok Tani / Usaha
    Route::get('/kelompok', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/kelompok', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/kelompok/{id}/assign-fasil', [GroupController::class, 'assignFacilitator'])->name('groups.assign_fasil');

    // 3. Tautan Laporan Harian (Daily Progress Report Links)
    Route::get('/tautan-laporan', [ReportLinkController::class, 'index'])->name('report_links.index');
    Route::post('/tautan-laporan', [ReportLinkController::class, 'store'])->name('report_links.store');
    Route::put('/tautan-laporan/{id}', [ReportLinkController::class, 'update'])->name('report_links.update');
    Route::post('/tautan-laporan/{id}/toggle', [ReportLinkController::class, 'toggle'])->name('report_links.toggle');
    Route::delete('/tautan-laporan/{id}', [ReportLinkController::class, 'destroy'])->name('report_links.destroy');

    // 4. Laporan Progress Masuk (Daily Progress Reports Feed)
    Route::get('/laporan-progress', [ProgressReportController::class, 'index'])->name('reports.index');
    Route::post('/laporan-progress/{id}/status', [ProgressReportController::class, 'updateStatus'])->name('reports.update_status');
    Route::delete('/laporan-progress/{id}', [ProgressReportController::class, 'destroy'])->name('reports.destroy');

    // 5. Approval Queue (Kelompok, Konten, Data)
    Route::get('/persetujuan', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/persetujuan/{id}/verifikasi', [ApprovalController::class, 'verify'])->name('approvals.verify');

    // 6. Program Pendampingan
    Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/program', [ProgramController::class, 'store'])->name('programs.store');

    // 7. Notifikasi (WhatsApp & Email Switch, Log Status, Test Send)
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/toggle', [NotificationController::class, 'toggleChannel'])->name('notifications.toggle');
    Route::post('/notifikasi/test-wa', [NotificationController::class, 'testWhatsApp'])->name('notifications.test_wa');
    Route::post('/notifikasi/test-email', [NotificationController::class, 'testEmail'])->name('notifications.test_email');
});
