<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SetupController;

/*
|--------------------------------------------------------------------------
| Web Routes — KeuKita Finance Management UMKM
| PRD §6 business isolation, §8 Wizard, §10 IA, §50 Stack Laravel+Blade
|--------------------------------------------------------------------------
*/

// Landing → redirect to dashboard (or setup if no business)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Setup Wizard §8 — 5 step business creation
Route::get('/setup', [SetupController::class, 'index'])->name('setup');
Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

// Dashboard SPA — contains Dashboard, Transactions, Finance, Reports, Settings, Audit (§11-42)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Optional aliases for direct navigation (SPA handles view switching via data-view, but keep for SEO/bookmark)
Route::get('/income', fn() => redirect()->route('dashboard'))->name('income');
Route::get('/expense', fn() => redirect()->route('dashboard'))->name('expense');
Route::get('/transfer', fn() => redirect()->route('dashboard'))->name('transfer');
Route::get('/finance/accounts', fn() => redirect()->route('dashboard'))->name('accounts');
Route::get('/finance/assets', fn() => redirect()->route('dashboard'))->name('assets');
Route::get('/reports', fn() => redirect()->route('dashboard'))->name('reports');
