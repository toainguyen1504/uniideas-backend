<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportController;

Route::get('/export/submission/{submission}', [ExportController::class, 'exportIdeasBySubmission']);
