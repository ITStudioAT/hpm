<?php

use App\Models\Homepage;
use App\Models\Menu;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated requests when deleting a menu', function () {
    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $menu = Menu::create([
        'homepage_id' => $homepage->id,
        'name' => 'Main Menu',
        'path' => 'main-menu',
        'structure' => ['content' => []],
    ]);

    $response = $this->deleteJson("/api/admin/menus/{$menu->id}");

    $response->assertStatus(401);
    $this->assertDatabaseHas('homepages', ['id' => $menu->id]);
});

it('rejects authenticated users without the admin role when deleting a menu', function () {
    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $menu = Menu::create([
        'homepage_id' => $homepage->id,
        'name' => 'Main Menu',
        'path' => 'main-menu',
        'structure' => ['content' => []],
    ]);

    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson("/api/admin/menus/{$menu->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('homepages', ['id' => $menu->id]);
});

it('deletes a menu for admin users', function () {
    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $menu = Menu::create([
        'homepage_id' => $homepage->id,
        'name' => 'Main Menu',
        'path' => 'main-menu',
        'structure' => ['content' => []],
    ]);

    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->deleteJson("/api/admin/menus/{$menu->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('homepages', ['id' => $menu->id]);
});
