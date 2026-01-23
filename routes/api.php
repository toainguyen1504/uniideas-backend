<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')
    ->middleware(['api'])
    ->as('api.')
    ->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            include 'v1/api/user.php';
            include 'v1/api/role.php';
            include 'v1/api/category.php';
            include 'v1/api/department.php';
        });
        include 'v1/api/auth.php';
    });
