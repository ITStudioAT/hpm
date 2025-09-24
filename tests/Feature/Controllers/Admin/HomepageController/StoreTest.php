<?php

use App\Models\Homepage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

function makeUserWithOptionalRole(?string $role = null): User
{
    $user = User::create([
        'email' => uniqid('user_', true).'@example.test',
        'password' => Hash::make('password'),
        'first_name' => 'Test',
        'last_name' => 'User',
        'is_active' => 1,
    ]);

    if ($role !== null) {
        $user->assignRole($role);
    }

    return $user;
}

it('rejects unauthenticated requests', function () {
    $response = $this->postJson('/api/admin/homepages', [
        'name' => 'Landing Page',
        'structure' => [],
    ]);

    $response->assertStatus(401);
    $this->assertDatabaseCount('homepages', 0);
});

it('rejects authenticated users without the admin role', function () {
    $user = makeUserWithOptionalRole();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/admin/homepages', [
        'name' => 'Landing Page',
        'structure' => [],
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseCount('homepages', 0);
});

it('creates a homepage with normalized structure for admins', function () {
    $admin = makeUserWithOptionalRole('admin');

    Sanctum::actingAs($admin, ['*']);

    $payload = [
        'name' => 'Landing Page',
        'structure' => [
            'index' => [
                'id' => 42,
                'ignore' => 'drop-me',
            ],
            'fonts' => [
                'fontset' => 'modern',
            ],
            'junk' => 'something',
        ],
    ];

    $response = $this->postJson('/api/admin/homepages', $payload);

    $response->assertOk();

    $expectedStructure = [
        'index' => [
            'id' => 42,
        ],
        'fonts' => [
            'fontset' => 'modern',
        ],
        'colors' => [
            'colorset' => 'default',
        ],
    ];

    $response->assertJson([
        'name' => 'Landing Page',
        'type' => 'homepage',
        'structure' => $expectedStructure,
    ]);

    $homepage = Homepage::where('name', 'Landing Page')->first();

    expect($homepage)->not->toBeNull();
    expect($homepage->type)->toBe('homepage');
    expect($homepage->structure)->toMatchArray($expectedStructure);
    expect($homepage->structure)->not->toHaveKey('junk');
});

it('returns 403 when trying to reuse an existing homepage name', function () {
    $admin = makeUserWithOptionalRole('admin');
    Sanctum::actingAs($admin, ['*']);

    Homepage::create([
        'name' => 'Landing Page',
        'type' => 'homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $response = $this->postJson('/api/admin/homepages', [
        'name' => 'Landing Page',
        'structure' => [],
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseCount('homepages', 1);
});
