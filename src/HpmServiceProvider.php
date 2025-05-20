<?php

namespace Itstudioat\Hpm;

use Illuminate\Support\Facades\Route;
use Itstudioat\Hpm\Commands\HpmCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HpmServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('hpm')
            ->hasConfigFile()
            ->hasRoutes(['web', 'api'])
            ->hasViews();
    }


    public function packageRegistered()
    {

        // Publishing
        $this->publishes([
            __DIR__ . '/../routes' => base_path('/routes/vendor/hpm'),
            __DIR__ . '/../resources/css' => base_path('/resources/vendor/hpm/css'),
            __DIR__ . '/../resources/js' => base_path('/resources/vendor/hpm/js'),
            __DIR__ . '/../resources/plugins' => base_path('/resources/vendor/hpm/plugins'),
            __DIR__ . '/../resources/routes' => base_path('/resources/vendor/hpm/routes'),
            __DIR__ . '/../resources/views' => base_path('/resources/vendor/hpm/views'),

        ], 'hpm-all');
    }


    public function bootingPackage()
    {
        $this->loadRoutes();
        // Use app override path first (published views)
        $this->loadViewsFrom(resource_path('vendor/hpm/views'), 'hpm');

        // Fallback to package views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'hpm');
    }

    protected function loadRoutes()
    {

        if (file_exists(__DIR__ . '/../routes/vendor/hpm/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/vendor/hpm/web.php');
        }

        // Lade API-Routen ohne Middleware
        if (file_exists(__DIR__ . '/../routes/api.php')) {
            Route::prefix('api') // API-Routen mit 'api' Prefix
                ->group(__DIR__ . '/../routes/api.php');
        }
    }
}
