<?php

use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware('api.token')->prefix('translations')->group(function () {

    Route::get('/export', [TranslationController::class, 'export']);
    Route::post('/search', [TranslationController::class, 'search']);
    Route::apiResource('/', TranslationController::class);
    
});
