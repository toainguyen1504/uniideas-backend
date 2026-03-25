<?php

use App\Http\Controllers\Api\PermissionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('permission', PermissionController::class)->only(['index']);
