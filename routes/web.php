<?php

use Illuminate\Support\Facades\Route;

// Alles wird gethrottlet
Route::middleware(['web', 'throttle:global', 'throttle:web', 'web-allowed'])->group(function () {
    Route::get('/', function () {
        return view('hpm::homepage');
    });

    Route::get('/pv', function () {
        return view('hpm::pv_homepage');
    });

    Route::get('/hpm/admin/{any?}', function () {
        return view('hpm::admin');
    });
});
