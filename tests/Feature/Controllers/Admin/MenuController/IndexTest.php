<?php

use App\Models\Homepage;
use App\Models\Menu;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated access to the admin menu index', function () {
    $response = $this->getJson('/api/admin/menus?homepage_id=1');

    $response->assertStatus(401);
});

it('rejects authenticated users without the admin role from the menu index', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson('/api/admin/menus?homepage_id=1');

    $response->assertStatus(403);
    expect($response->json('message'))->toBeString();
});

it('requires a valid homepage id when listing menus', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->getJson('/api/admin/menus');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id']);

    $response = $this->getJson('/api/admin/menus?homepage_id=9999');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id']);
});

it('returns the menus for a homepage sorted by name for admins', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $otherHomepage = Homepage::create([
        'name' => 'Secondary Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $beta = Menu::create([
        'homepage_id' => $homepage->id,
        'name' => 'Beta Menu',
        'path' => 'beta-menu',
        'structure' => ['content' => ['items' => [['label' => 'Beta']]]],
    ]);

    $alpha = Menu::create([
        'homepage_id' => $homepage->id,
        'name' => 'Alpha Menu',
        'path' => 'alpha-menu',
        'structure' => ['content' => ['items' => [['label' => 'Alpha']]]],
    ]);

    Menu::create([
        'homepage_id' => $otherHomepage->id,
        'name' => 'Other Homepage Menu',
        'path' => 'other-menu',
        'structure' => ['content' => []],
    ]);

    $response = $this->getJson('/api/admin/menus?homepage_id=' . $homepage->id);

    $response->assertOk();

    $payload = $response->json();
    expect($payload)->toHaveCount(2);

    expect($payload[0]['id'])->toBe($alpha->id);
    expect($payload[0]['name'])->toBe('Alpha Menu');
    expect($payload[0]['path'])->toBe('alpha-menu');
    expect($payload[0]['type'])->toBe('menu');

    expect($payload[1]['id'])->toBe($beta->id);
    expect($payload[1]['name'])->toBe('Beta Menu');

    $ids = collect($payload)->pluck('id')->all();
    expect($ids)->toBe([$alpha->id, $beta->id]);
});
