<?php

use Illuminate\Support\Facades\Route;

Route::get('/decode', [\App\Http\Controllers\ShortenerController::class, 'show'])->name('shortener.show');
Route::post('/encode', [\App\Http\Controllers\ShortenerController::class, 'store'])->name('shortener.store');
