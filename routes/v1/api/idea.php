<?php

use App\Http\Controllers\Api\IdeaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('idea', IdeaController::class);
Route::get('/ideas/filter/{type}', [IdeaController::class, 'list']);
Route::post('idea/{idea}/approve', [IdeaController::class, 'approve'])->name('idea.approve');
