<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\AuthController;

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// Package Routes - Public (Get All & Get Detail)
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);

// Package Routes - Protected (Create, Update, Delete)
Route::middleware('jwt.auth')->group(function () {
    Route::post('/packages', [PackageController::class, 'store']);
    Route::put('/packages/{id}', [PackageController::class, 'update']);
    Route::delete('/packages/{id}', [PackageController::class, 'destroy']);
});
