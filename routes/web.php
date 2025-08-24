<?php

use App\Services\FonttypeService;
use Illuminate\Support\Facades\Route;



Route::middleware(['throttle:global', 'throttle:web'])->group(function () {
    Route::get('/fontset/{fontset}.css', function (string $fontset, FonttypeService $svc) {
        return $svc->serve($fontset);
    })->name('fontset.css');

    // Route::get('/api/css/colors/{slug}.css', [\App\Http\Controllers\Homepage\ColorsetController::class, 'css']);
    // Route::get('/api/colorsets', [ColorsetController::class, 'list']);
});

Route::middleware(['throttle:global', 'throttle:web', 'web-allowed'])->group(function () {

    /***** ADMIN ROUTES *****/
    /* auth-routes */
    Route::get('/admin/login', function () {
        return view('spa::admin');
    })->name('login');
    Route::get('/admin/unknown_password', function () {
        return view('spa::admin');
    });
    Route::get('/admin/register', function () {
        abort_unless(config('spa.register_admin_allowed'), 403);
        return view('spa::admin');
    });
    Route::get('/admin/email_verification', function () {
        return view('spa::admin');
    });

    /*
    Route::get('/admin/font-preview', function () {
        return view('font-preview'); // <-- no SPA, just the blade above
    })->middleware(['auth:sanctum']);
*/
    Route::get('/admin/color-fontset-preview', function () {
        return view('color-fontset-preview'); // <-- zeigt resources/views/color-preview.blade.php
    })->middleware(['auth:sanctum']);

    /* restliche admin-Routen */
    Route::get('/admin/{any?}', function () {
        return view('spa::admin');
    })->where('any', '.*')->middleware(['auth:sanctum']);


    /* APPLICATION ROUTES */
    Route::get('/application/{any?}', function () {
        return view('spa::application');
    })->where('any', '.*');


    Route::get('/homepage/example/{any?}', function () {
        return view('homepage');
    });

    Route::get('/{any?}', function () {
        return view('homepage');
    });
});
