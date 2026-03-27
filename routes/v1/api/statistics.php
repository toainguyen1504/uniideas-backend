<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatisticsController;

Route::get('statistics', [StatisticsController::class, 'index']);

    
