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
            /*
            ->hasRoutes(['web'])
            */
            ->hasCommand(HpmCommand::class)
            ->hasViews();
    }


    public function packageRegistered()
    {

        // Publishing
        $this->publishes([
            __DIR__ . '/../routes' => base_path('/routes/vendor/hpm'),
            __DIR__ . '/../resources/css' => base_path('/resources/vendor/hpm/css'),
            __DIR__ . '/../resources/js' => base_path('/resources/vendor/hpm/js'),
            __DIR__ . '/../resources/hpm' => base_path('/resources/vendor/hpm/hpm'),
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

        $publishedRoutePath = base_path('routes/vendor/hpm/web.php');
        $defaultRoutePath = __DIR__ . '/../routes/web.php';

        if (file_exists($publishedRoutePath)) {
            $this->loadRoutesFrom($publishedRoutePath);
        } elseif (file_exists($defaultRoutePath)) {
            $this->loadRoutesFrom($defaultRoutePath);
        }

        // Lade API-Routen ohne Middleware
        /*      */
        if (file_exists(__DIR__ . '/../routes/api.php')) {
            Route::prefix('api') // API-Routen mit 'api' Prefix
                ->group(__DIR__ . '/../routes/api.php');
        }
    }
}
