<?php

use App\Http\Controllers\Api\IdeaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('idea', IdeaController::class);
