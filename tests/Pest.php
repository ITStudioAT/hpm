<?php

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(TestCase::class, RefreshDatabase::class)->beforeEach(function () {

    // Optional: Middleware deaktivieren
    $this->withoutMiddleware([
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
    ]);

    $this->createRoles();
    $this->createUsers();
})->group('integration')->in('Feature');



// (optional) für Unit-Tests:
uses(TestCase::class)
    ->in('Unit');
