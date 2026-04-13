<?php

use App\Http\Controllers\Api\AgamaController;
use App\Http\Controllers\Api\BahasaController;
use App\Http\Controllers\Api\SukuController;
use App\Http\Controllers\Api\CaraBayarController;
use App\Http\Controllers\Api\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/agama', [AgamaController::class, 'index']);
// Route::get('/bahasa', [BahasaController::class, 'index']);
// Route::get('/suku', [SukuController::class, 'index']);
// Route::get('/cara-bayar', [CaraBayarController::class, 'index']);
// Old route removed: Route::get('/patient/search', [PatientController::class, 'search']);
