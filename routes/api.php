<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/time-in', [TimeController::class, 'timeIn']);
    Route::post('/time-out', [TimeController::class, 'timeOut']);
    Route::get('/total-time', [TimeController::class, 'totalTime']);
});

