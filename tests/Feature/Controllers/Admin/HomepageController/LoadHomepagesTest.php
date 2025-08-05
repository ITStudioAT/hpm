
<?php

use App\Models\User;
use App\Models\Homepage;
use App\Services\AdminNavigationService;


beforeEach(function () {
    // This runs before every test in this file
    \App\Models\Homepage::factory()->count(10)->create([
        'type' => 'index'
    ]);

    \App\Models\Homepage::factory()->count(10)->create([
        'type' => 'other'
    ]);
});

function runLoadHomepagesTest()
{

    $response = test()->getJson('/api/admin/homepage/index');

    // Assert: Status 200
    $response->assertStatus(200);

    // Get all homepages from DB (expected count)
    $expectedCount = Homepage::where('type', 'index')->count();

    // Assert returned count matches DB
    $actualCount = count($response->json());
    expect($actualCount)->toBe($expectedCount);

    $response->assertJsonStructure([
        '*' => [
            'id',
            'name',
            'path',
            'type',
            'structure',
        ]
    ]);
}

it('assert 200 for super_admin and admin /api/admin/homepage/index', function () {
    // super_admin
    $this->actingAs($this->super_admin, 'sanctum');
    runLoadHomepagesTest();

    // admin
    $this->actingAs($this->admin, 'sanctum');
    runLoadHomepagesTest();
});

it('assert forbidden for user  /api/admin/homepage/index', function () {
    // user
    $this->actingAs($this->user, 'sanctum');
    $response = test()->getJson('/api/admin/homepage/index');
    $response->assertForbidden();
});


it('assert unauthorized for guests  /api/admin/homepage/index', function () {
    // guest
    $response = test()->getJson('/api/admin/homepage/index');
    $response->assertUnauthorized();
});
