<?php

use App\Http\Controllers\Api\ReactController;
use Illuminate\Support\Facades\Route;

Route::apiResource('react', ReactController::class);
Route::post('react/{idea}/toggle', [ReactController::class, 'toggleReact'])->name('react.toggle');
