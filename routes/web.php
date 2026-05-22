<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return redirect('/1');
});

Route::get('/{user}', [ChatController::class, 'index']);

Route::post('/send-message/{user}', [ChatController::class, 'send']);