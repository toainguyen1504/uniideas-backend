<?php

use App\Http\Controllers\Api\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/mail', [MailController::class, 'index']);
Route::post('/mail', [MailController::class, 'sendMail'])->name('send_mail');
