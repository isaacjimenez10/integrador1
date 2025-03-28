<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\SensorDataController;


Route::prefix('sensoresdata')->group(function () {
    Route::post('/', [SensorDataController::class, 'store']); // POST /api/sensoresdata
    Route::get('/', [SensorDataController::class, 'index']);  // GET /api/sensoresdata
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
