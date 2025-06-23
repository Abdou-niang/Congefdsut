<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GmailController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/hi', function () {
    return "ehhh";
});

Route::get('/google/login', [GmailController::class, 'redirectToGoogle']);
Route::get('/callback', [GmailController::class, 'handleGoogleCallback']);
Route::get('/send-gmail', [GmailController::class, 'sendMail']);
Route::get('/jours_feries', [GmailController::class, 'getJoursFerier']);
