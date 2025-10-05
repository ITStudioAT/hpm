<?php

use App\Models\Homepage;
use App\Models\Menu;
use Laravel\Sanctum\Sanctum;

function createMenuHomepage(string $name = 'Main Homepage'): Homepage
{
    return Homepage::create([
        'name' => $name,
        'structure' => config('hpm.structures.homepage'),
    ]);
}

function createMenuFixture(Homepage $homepage, array $overrides = []): Menu
{
    return Menu::create(array_merge([
        'homepage_id' => $homepage->id,
        'name' => 'Primary Menu',
        'path' => 'primary-menu',
        'structure' => config('hpm.structures.menu'),
    ], $overrides));
}

it('rejects unauthenticated requests when updating a menu', function () {
    $homepage = createMenuHomepage();
    $menu = createMenuFixture($homepage);

    $response = $this->putJson("/api/admin/menus/{$menu->id}", [
        'id' => $menu->id,
        'name' => 'Updated Menu',
    ]);

    $response->assertStatus(401);

    $menu->refresh();
    expect($menu->name)->toBe('Primary Menu');
});

it('rejects authenticated users without the admin role when updating a menu', function () {
    $homepage = createMenuHomepage();
    $menu = createMenuFixture($homepage);

    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson("/api/admin/menus/{$menu->id}", [
        'id' => $menu->id,
        'name' => 'Updated Menu',
    ]);

    $response->assertStatus(403);

    $menu->refresh();
    expect($menu->name)->toBe('Primary Menu');
});

it('validates the uniqueness of the menu name during update', function () {
    $homepage = createMenuHomepage();
    $menu = createMenuFixture($homepage, [
        'name' => 'Main Navigation',
        'path' => 'main-navigation',
    ]);

    createMenuFixture($homepage, [
        'name' => 'Existing Menu',
        'path' => 'existing-menu',
    ]);

    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->putJson("/api/admin/menus/{$menu->id}", [
        'id' => $menu->id,
        'name' => 'Existing Menu',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});

it('validates the uniqueness of the menu path during update', function () {
    $homepage = createMenuHomepage();
    $menu = createMenuFixture($homepage, [
        'name' => 'Main Navigation',
        'path' => 'main-navigation',
    ]);

    createMenuFixture($homepage, [
        'name' => 'Secondary Menu',
        'path' => 'secondary-menu',
    ]);

    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->putJson("/api/admin/menus/{$menu->id}", [
        'id' => $menu->id,
        'name' => 'Main Navigation',
        'path' => 'secondary-menu',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['path']);
});

it('updates a menu for admin users', function () {
    $homepage = createMenuHomepage();
    $menu = createMenuFixture($homepage, [
        'name' => 'Main Navigation',
        'path' => 'main-navigation',
        'structure' => config('hpm.structures.menu'),
    ]);

    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $payload = [
        'id' => $menu->id,
        'homepage_id' => $homepage->id,
        'name' => 'Updated Navigation',
        'path' => 'updated-navigation',
        'type' => 'menu',
        'structure' => [
            'content' => [
                ['label' => 'Home', 'is_visible' => true],
                ['label' => 'Contact', 'is_visible' => false],
            ],
        ],
    ];

    $response = $this->putJson("/api/admin/menus/{$menu->id}", $payload);

    $response->assertOk();

    $response->assertJson([
        'id' => $menu->id,
        'homepage_id' => $homepage->id,
        'name' => 'Updated Navigation',
        'path' => 'updated-navigation',
        'type' => 'menu',
        'structure' => config('hpm.structures.menu'),
    ]);

    $menu->refresh();
    expect($menu->name)->toBe('Updated Navigation');
    expect($menu->path)->toBe('updated-navigation');
    expect($menu->structure)->toMatchArray(config('hpm.structures.menu'));
});
