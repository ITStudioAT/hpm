<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Itstudioat\Hpm\src\Http\Controllers\Homepage\HomepageController;





// Globales Throttle
Route::prefix('hpm')->middleware(['throttle:global', 'throttle:api'])->group(function () {
    Route::get('/homepage/get_config',  [HomepageController::class, 'getConfig']);
    Route::get('/homepage/load_homepage',  [HomepageController::class, 'loadHomepage']);
});
