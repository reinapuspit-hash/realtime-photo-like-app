<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;

Route::get('/', [PhotoController::class, 'index']);

Route::post('/like/{photo}', [PhotoController::class, 'like']);