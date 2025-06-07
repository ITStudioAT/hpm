<?php

use Illuminate\Support\Facades\Route;
use Itstudioat\Hpm\src\Http\Controllers\Admin\AdminController;
use Itstudioat\Hpm\src\Http\Controllers\Homepage\HomepageController;





// Globales Throttle
Route::prefix('hpm')->middleware(['web', 'throttle:global', 'throttle:api'])->group(function () {
    Route::get('/homepage/get_config',  [HomepageController::class, 'getConfig']);
    Route::get('/homepage/load_homepage',  [HomepageController::class, 'loadHomepage']);

    Route::get('/admin/get_config',  [AdminController::class, 'getConfig']);
    Route::get('/admin/get_hpm',  [AdminController::class, 'getHpm']);
    Route::post('/admin/save_hpm',  [AdminController::class, 'saveHpm']);
});
