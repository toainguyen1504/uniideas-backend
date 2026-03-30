<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
Route::apiResource('user', UserController::class);
