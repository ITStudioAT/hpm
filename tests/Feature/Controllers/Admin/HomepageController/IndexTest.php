<?php

use App\Models\Homepage;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function makeUserWithRole(?string $role = null): User
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

it('rejects unauthenticated access to the admin homepage index', function () {
    $response = $this->getJson('/api/admin/homepages');

    $response->assertStatus(401);
});

it('rejects authenticated users without the admin role', function () {
    $user = makeUserWithRole();
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson('/api/admin/homepages');

    $response->assertStatus(403);
    expect($response->json('message'))->toBeString();
});

it('returns all homepage resources ordered by name for admins', function () {
    $admin = makeUserWithRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $beta = Homepage::create([
        'name' => 'Beta Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Beta']],
    ]);

    $alpha = Homepage::create([
        'name' => 'Alpha Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Alpha']],
    ]);

    Homepage::create([
        'name' => 'Landing Page',
        'type' => 'landing',
    ]);

    $response = $this->getJson('/api/admin/homepages');

    $response->assertOk();

    $payload = $response->json();
    expect($payload)->toHaveCount(2);

    expect($payload[0]['id'])->toBe($alpha->id);
    expect($payload[0]['name'])->toBe('Alpha Home');
    expect($payload[0]['type'])->toBe('homepage');
    expect($payload[0]['structure'])->toMatchArray(['index' => ['title' => 'Alpha']]);

    expect($payload[1]['id'])->toBe($beta->id);
    expect($payload[1]['name'])->toBe('Beta Home');
    expect($payload[1]['type'])->toBe('homepage');

    $ids = collect($payload)->pluck('id')->all();
    expect($ids)->toBe([$alpha->id, $beta->id]);
});
