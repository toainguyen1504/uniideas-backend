<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('user', UserController::class);
Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
