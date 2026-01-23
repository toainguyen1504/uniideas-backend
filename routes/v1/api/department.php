<?php

use App\Http\Controllers\Api\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('department', DepartmentController::class);
Route::get('department/{department}/users', [DepartmentController::class, 'getUsersOfDepartment']);
