<?php

use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated access to the admin page index', function () {
    $response = $this->getJson('/api/admin/pages?homepage_id=1');

    $response->assertStatus(401);
});

it('rejects authenticated users without the admin role from the page index', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson('/api/admin/pages?homepage_id=1');

    $response->assertStatus(403);
    expect($response->json('message'))->toBeString();
});

it('requires a valid homepage id when listing pages', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->getJson('/api/admin/pages');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id']);

    $response = $this->getJson('/api/admin/pages?homepage_id=9999');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id']);
});

it('returns the pages for a homepage sorted by name for admins', function () {
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

    $beta = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Beta Page',
        'path' => 'beta-page',
        'type' => 'page',
        'structure' => [
            'header' => ['id' => 2, 'is_visible' => true],
            'content' => [],
            'footer' => ['id' => 2, 'is_visible' => true],
        ],
    ]);

    $alpha = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Alpha Page',
        'path' => 'alpha-page',
        'type' => 'index',
        'structure' => [
            'header' => ['id' => 1, 'is_visible' => true],
            'content' => [],
            'footer' => ['id' => 1, 'is_visible' => true],
        ],
    ]);

    Page::create([
        'homepage_id' => $otherHomepage->id,
        'name' => 'Other Homepage Page',
        'path' => 'other-page',
        'type' => 'page',
        'structure' => [
            'header' => ['id' => 3, 'is_visible' => true],
            'content' => [],
            'footer' => ['id' => 3, 'is_visible' => true],
        ],
    ]);

    $response = $this->getJson('/api/admin/pages?homepage_id=' . $homepage->id);

    $response->assertOk();

    $payload = $response->json();
    expect($payload)->toHaveCount(2);

    expect($payload[0]['id'])->toBe($alpha->id);
    expect($payload[0]['name'])->toBe('Alpha Page');
    expect($payload[0]['path'])->toBe('alpha-page');
    expect($payload[0]['type'])->toBe('index');

    expect($payload[1]['id'])->toBe($beta->id);
    expect($payload[1]['name'])->toBe('Beta Page');

    $ids = collect($payload)->pluck('id')->all();
    expect($ids)->toBe([$alpha->id, $beta->id]);
});
