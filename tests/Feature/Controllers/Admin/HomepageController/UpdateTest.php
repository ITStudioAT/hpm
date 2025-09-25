<?php

use App\Models\Homepage;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

if (! function_exists('makeActiveUserWithRole')) {
    function makeActiveUserWithRole(?string $role = null): User
    {
        $user = makeUser([
            'email' => uniqid('user_', true).'@example.test',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);

        if ($role !== null) {
            $user->assignRole($role);
        }

        return $user;
    }
}

it('rejects unauthenticated users from updating a homepage', function () {
    $homepage = Homepage::create([
        'name' => 'Original Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Original']],
    ]);

    $response = $this->putJson("/api/admin/homepages/{$homepage->id}", [
        'id' => $homepage->id,
        'name' => 'Updated Home',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseHas('homepages', [
        'id' => $homepage->id,
        'name' => 'Original Home',
    ]);
});

it('rejects authenticated non-admin users from updating a homepage', function () {
    $user = makeActiveUserWithRole();
    Sanctum::actingAs($user, ['*']);

    $homepage = Homepage::create([
        'name' => 'Original Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Original']],
    ]);

    $response = $this->putJson("/api/admin/homepages/{$homepage->id}", [
        'id' => $homepage->id,
        'name' => 'Updated Home',
    ]);

    $response->assertStatus(403);

    $this->assertDatabaseHas('homepages', [
        'id' => $homepage->id,
        'name' => 'Original Home',
    ]);
});

it('updates a homepage when requested by an admin', function () {
    $admin = makeActiveUserWithRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Original Home',
        'path' => '/home-original',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Original']],
    ]);

    $payload = [
        'id' => $homepage->id,
        'name' => 'Updated Home',
        'path' => '/updated-home',
        'type' => 'homepage',
        'structure' => [
            'index' => ['title' => 'Updated'],
            'fonts' => ['fontset' => 'modern'],
        ],
    ];

    $response = $this->putJson("/api/admin/homepages/{$homepage->id}", $payload);

    $response->assertOk();
    $response->assertJson([
        'id' => $homepage->id,
        'name' => 'Updated Home',
        'path' => '/updated-home',
        'type' => 'homepage',
        'structure' => [
            'index' => ['title' => 'Updated'],
            'fonts' => ['fontset' => 'modern'],
        ],
    ]);

    $this->assertDatabaseHas('homepages', [
        'id' => $homepage->id,
        'name' => 'Updated Home',
        'path' => '/updated-home',
    ]);

    expect($homepage->fresh()->structure)->toMatchArray([
        'index' => ['title' => 'Updated'],
        'fonts' => ['fontset' => 'modern'],
    ]);
});
