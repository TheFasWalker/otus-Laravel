<?php

use App\Http\Controllers\admin\api\TagController;
use App\Http\Controllers\api\v1\AuthController;
use Illuminate\Support\Facades\Route;




Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // Здесь можно добавить другие защищенные роуты
    // Route::apiResource('products', ProductController::class);
});