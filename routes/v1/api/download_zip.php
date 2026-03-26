<?php

use App\Http\Controllers\Api\DownloadZipController;
use Illuminate\Support\Facades\Route;

Route::get('/submissions/{submission}/download-zip', [DownloadZipController::class, 'downloadSubmission'])->name('api.download_zip');
