<?php

use Illuminate\Support\Facades\Route;

// Alles wird gethrottlet
Route::middleware(['throttle:global', 'throttle:web', 'web-allowed'])->group(function () {
    Route::get('/', function () {
        return view('hpm::homepage');
    });

    Route::get('/hpm/admin', function () {
        return view('hpm::admin');
    });
});
