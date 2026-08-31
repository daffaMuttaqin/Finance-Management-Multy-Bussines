<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — KeuKita Finance Management UMKM
| Breeze Auth + Finance SPA (§6, §8, §10, §50)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Finance — requires auth (PRD §4 Owner/Admin)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard SPA — §11-42
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Setup Wizard §8
    Route::get('/setup', [SetupController::class, 'index'])->name('setup');
    Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

    // SPA aliases (view switching via data-view)
    Route::get('/income', fn() => redirect()->route('dashboard'))->name('income');
    Route::get('/expense', fn() => redirect()->route('dashboard'))->name('expense');
    Route::get('/transfer', fn() => redirect()->route('dashboard'))->name('transfer');
    Route::get('/finance/accounts', fn() => redirect()->route('dashboard'))->name('accounts');
    Route::get('/finance/assets', fn() => redirect()->route('dashboard'))->name('assets');
    Route::get('/reports', fn() => redirect()->route('dashboard'))->name('reports');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
