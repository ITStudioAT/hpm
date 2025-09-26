<?php

use App\Models\Homepage;
use App\Models\Menu;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated requests when creating a menu', function () {
    $response = $this->postJson('/api/admin/menus', [
        'homepage_id' => 1,
        'data' => ['name' => 'Main Menu'],
    ]);

    $response->assertStatus(401);
    $this->assertDatabaseCount('homepages', 0);
});

it('rejects authenticated users without the admin role when creating a menu', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/admin/menus', [
        'homepage_id' => 1,
        'data' => ['name' => 'Main Menu'],
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseCount('homepages', 0);
});

it('validates the required fields when storing a menu', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->postJson('/api/admin/menus', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id', 'data.name']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $response = $this->postJson('/api/admin/menus', [
        'homepage_id' => $homepage->id,
        'data' => ['name' => ''],
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data.name']);
});

it('rejects duplicate menu names for admins', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    Menu::create([
        'homepage_id' => $homepage->id,
        'name' => 'Main Menu',
        'path' => 'main-menu',
        'structure' => ['content' => []],
    ]);

    $response = $this->postJson('/api/admin/menus', [
        'homepage_id' => $homepage->id,
        'data' => ['name' => 'Main Menu'],
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data.name']);
});

it('creates a menu with normalized structure for admins', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'data' => [
            'name' => 'Main Menu',
        ],
        'structure' => [
            'content' => [
                ['label' => 'Home', 'is_visible' => 'true'],
            ],
            'extra' => 'ignored',
        ],
    ];

    $response = $this->postJson('/api/admin/menus', $payload);

    $response->assertOk();

    $response->assertJson([
        'homepage_id' => $homepage->id,
        'name' => 'Main Menu',
        'path' => null,
        'type' => 'menu',
        'structure' => [
            'content' => [],
        ],
    ]);

    $menuId = $response->json('id');
    expect($menuId)->not->toBeNull();

    $this->assertDatabaseHas('homepages', [
        'id' => $menuId,
        'homepage_id' => $homepage->id,
        'name' => 'Main Menu',
        'type' => 'menu',
    ]);

    $menu = Menu::find($menuId);
    expect($menu)->not->toBeNull();
    expect($menu->structure)->toMatchArray(['content' => []]);
});
