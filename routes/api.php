<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AuditController;

/*
|--------------------------------------------------------------------------
| API Routes — KeuKita §6 business_id isolation, §38-41 security
| Prefix /api handled by bootstrap/app.php
|--------------------------------------------------------------------------
*/

Route::get('/health', fn() => response()->json(['status'=>'ok','app'=>'KeuKita','version'=>'1.0-mvp']));

// Business §8-9
Route::get('/business', [BusinessController::class,'index']);
Route::post('/business', [BusinessController::class,'store']);
Route::get('/business/{business}', [BusinessController::class,'show']);
Route::put('/business/{business}', [BusinessController::class,'update']);

// Accounts §19-20
Route::get('/accounts', [AccountController::class,'index']);
Route::post('/accounts', [AccountController::class,'store']);
Route::put('/accounts/{account}', [AccountController::class,'update']);
Route::post('/accounts/{account}/archive', [AccountController::class,'archive']);

// Categories §30
Route::get('/categories', [CategoryController::class,'index']);
Route::post('/categories', [CategoryController::class,'store']);
Route::put('/categories/{category}', [CategoryController::class,'update']);
Route::post('/categories/{category}/archive', [CategoryController::class,'archive']);
Route::delete('/categories/{category}', [CategoryController::class,'destroy']);

// Transactions §16-18, §40 void
Route::get('/transactions', [TransactionController::class,'index']);
Route::post('/transactions', [TransactionController::class,'store']);
Route::get('/transactions/{transaction}', [TransactionController::class,'show']);
Route::put('/transactions/{transaction}', [TransactionController::class,'update']);
Route::post('/transactions/{transaction}/void', [TransactionController::class,'void']);

// Assets §21
Route::get('/assets', [AssetController::class,'index']);
Route::post('/assets', [AssetController::class,'store']);

// Reports §32-34
Route::get('/reports/summary', [ReportController::class,'summary']);
Route::get('/reports/dashboard', [ReportController::class,'dashboard']);
Route::get('/reports/export', [ReportController::class,'export']);

// Audit §39
Route::get('/audit', [AuditController::class,'index']);
