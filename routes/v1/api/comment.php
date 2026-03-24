<?php

use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('comment', CommentController::class);
Route::get('comments', [CommentController::class, 'listComments']);
