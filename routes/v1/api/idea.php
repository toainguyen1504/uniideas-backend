<?php

use App\Http\Controllers\Api\IdeaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('idea', IdeaController::class)->except(['update']);
Route::post('idea/{idea}', [IdeaController::class, 'update'])->name('idea.update');
Route::get('/ideas/filter/{filter}', [IdeaController::class, 'list']);
Route::post('idea/{idea}/approve', [IdeaController::class, 'approve'])->name('idea.approve');
