<?php

use Tests\TestCase;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature (integration) tests:
 * - refresh DB
 * - disable throttling middleware (optional)
 * - reset Spatie cache, create roles/users
 */
uses(TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        // Optional: Middleware deaktivieren nur für Feature
        $this->withoutMiddleware([
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
        ]);

        // Spatie permission cache leeren
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Basisdaten
        $this->createRoles();
        $this->createUsers();
    })
    ->group('integration')
    ->in('Feature');

/**
 * Unit tests:
 * - refresh DB (because you create users/roles)
 * - reset Spatie cache, create roles/users
 *   (remove these if some “pure” unit tests don’t need the DB)
 */
uses(TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->createRoles();
        $this->createUsers();
    })
    ->group('unit')
    ->in('Unit');
