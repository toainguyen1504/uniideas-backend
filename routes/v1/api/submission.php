<?php

use App\Http\Controllers\Api\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('submission',SubmissionController::class);
Route::get('submission/{submission}/ideas', [SubmissionController::class, 'ideasIsFeatured'])->name('submission.ideas');
