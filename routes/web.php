<?php

use Illuminate\Support\Facades\Route;

Route::get('/shortener/create', [\App\Http\Controllers\ShortenerController::class, 'create'])->name('shortener.create');
