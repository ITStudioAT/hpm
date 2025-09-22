<?php

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

if (! function_exists('ensurePermissionTables')) {
    /**
     * Make sure Spatie's permission tables exist in the test DB.
     * Useful if you didn't publish their migrations in your app.
     */
    function ensurePermissionTables(): void
    {
        Artisan::call('migrate', [
            '--path'     => 'vendor/spatie/laravel-permission/database/migrations',
            '--realpath' => true,
            '--force'    => true,
        ]);

        // Clear the cached roles/permissions between tests.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

if (! function_exists('makeUser')) {
    /**
     * Create a minimal user (no seeder required).
     * Override fields by passing an associative array.
     */
    function makeUser(array $overrides = []): User
    {
        User::unguard();

        return User::create(array_merge([
            'first_name' => 'Test',
            'last_name'  => 'User',
            'email'      => 'test@example.com',
            'password'   => bcrypt('secret'),
        ], $overrides));
    }
}

if (! function_exists('setHomepageSchema')) {
    /**
     * Set the hpm.structures config used by UpdateService tests.
     * Tweak this to mirror your real config shape.
     */
    function setHomepageSchema(): void
    {
        config()->set('hpm.structures', [
            'homepage' => [
                'index'  => ['id' => null],
                'fonts'  => ['fontset' => 'default'],
                'colors' => ['colorset' => 'default'],
            ],
            // Add other types here if you test them:
            // 'landing' => [
            //     'hero'  => ['id' => null],
            //     'theme' => ['palette' => 'light'],
            // ],
        ]);
    }
}
