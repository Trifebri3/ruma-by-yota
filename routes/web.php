<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilitatorController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RumaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication & Multi-Role Login / Register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/login/quick/{role}', [AuthController::class, 'quickRoleLogin'])->name('login.quick');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth (Socialite & Google Sign-In)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Quick Demo Persona Switcher
Route::get('/switch-persona/{roleKey}', [RumaController::class, 'switchPersona'])->name('switch.persona');

// Alias for root 'home' named route
Route::get('/beranda', function () {
    return redirect()->route('public.home');
})->name('home');

// PWA Routes
Route::get('/manifest.json', function () {
    $content = json_decode(file_get_contents(public_path('manifest.json')), true);

    return response()->json($content);
});
Route::get('/offline.html', function () {
    return response(file_get_contents(public_path('offline.html')));
});

// Modular Route Files
require __DIR__.'/public.php';
require __DIR__.'/admin.php';
require __DIR__.'/facilitator.php';
require __DIR__.'/group.php';

// Backwards-Compatible Aliases for Legacy Routes
Route::prefix('kelompok')->middleware(['role:user,admin'])->group(function () {
    Route::get('/beranda', [RumaController::class, 'dashboard'])->name('ruma.dashboard');
    Route::get('/belajar', [RumaController::class, 'belajar'])->name('ruma.belajar');
    Route::get('/usaha', [RumaController::class, 'usaha'])->name('ruma.usaha');
    Route::get('/marketing', [RumaController::class, 'marketing'])->name('ruma.marketing');
    Route::get('/profil', [RumaController::class, 'kelompok'])->name('ruma.kelompok');

    Route::post('/keuangan/simpan', [FinancialController::class, 'store'])->name('ruma.financial.store');
    Route::post('/produk/simpan', [ProductController::class, 'store'])->name('ruma.product.store');
    Route::post('/marketing/kumpulkan', [MarketingController::class, 'submit'])->name('ruma.marketing.submit');
    Route::post('/lahan/simpan', [LandController::class, 'store'])->name('ruma.land.store');
});

Route::prefix('siyota')->middleware(['role:fasil,admin'])->group(function () {
    Route::get('/dashboard', [FacilitatorController::class, 'dashboard'])->name('facilitator.dashboard.legacy');
    Route::get('/kelompok/{id}', [FacilitatorController::class, 'groupDetail'])->name('facilitator.group.detail');
    Route::post('/submissions/{id}/review', [FacilitatorController::class, 'reviewSubmission'])->name('facilitator.submission.review');
    Route::post('/products/{id}/evaluate', [FacilitatorController::class, 'evaluateProduct'])->name('facilitator.product.evaluate.legacy');
    Route::post('/groups/{id}/verify', [FacilitatorController::class, 'verifyGroup'])->name('facilitator.group.verify');
    Route::post('/mentoring/store', [FacilitatorController::class, 'storeMentoringNote'])->name('facilitator.mentoring.store.legacy');
});
