<?php

use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated requests when creating a page', function () {
    $response = $this->postJson('/api/admin/pages', [
        'homepage_id' => 1,
        'data' => [
            'name' => 'About Page',
            'path' => 'about-page',
        ],
        'folder' => '/',
    ]);

    $response->assertStatus(401);
    $this->assertDatabaseCount('homepages', 0);
});

it('rejects authenticated users without the admin role when creating a page', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/admin/pages', [
        'homepage_id' => 1,
        'data' => [
            'name' => 'About Page',
            'path' => 'about-page',
        ],
        'folder' => '/',
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseCount('homepages', 0);
});

it('validates the homepage id when storing a page', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->postJson('/api/admin/pages', [
        'homepage_id' => 9999,
        'data' => [
            'name' => 'About Page',
            'path' => 'about-page',
        ],
        'folder' => '/',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id']);
});

it('creates a page with a sanitized path and normalized structure for admins', function () {
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
            'name' => 'About Us',
            'path' => '  /About Team?!  ',
        ],
        'structure' => [
            'header' => [
                'id' => 42,
                'is_visible' => 'false',
                'drop' => 'ignored',
            ],
            'content' => [],
            'footer' => [
                'id' => 7,
                'is_visible' => 'TRUE',
            ],
            'junk' => ['remove' => true],
        ],
        'folder' => '/',
    ];

    $response = $this->postJson('/api/admin/pages', $payload);

    $response->assertOk();

    $expectedStructure = [
        'header' => ['id' => 42, 'is_visible' => 'false'],
        'content' => [],
        'footer' => ['id' => 7, 'is_visible' => 'TRUE'],
    ];

    $response->assertJson([
        'homepage_id' => $homepage->id,
        'name' => 'About Us',
        'path' => 'About-Team',
        'type' => 'page',
        'structure' => $expectedStructure,
        'folder' => '/',
    ]);

    $pageId = $response->json('id');
    expect($pageId)->not->toBeNull();

    $this->assertDatabaseHas('homepages', [
        'id' => $pageId,
        'homepage_id' => $homepage->id,
        'name' => 'About Us',
        'path' => 'About-Team',
        'type' => 'page',
        'folder' => '/',
    ]);

    $page = Page::find($pageId);
    expect($page)->not->toBeNull();
    expect($page->structure)->toMatchArray($expectedStructure);
    expect($page->structure)->not->toHaveKey('junk');
    expect($page->folder)->toBe('/');
});

it('returns 422 when attempting to reuse an existing page name', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'About Page',
        'path' => 'about-page',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/',
    ]);

    $response = $this->postJson('/api/admin/pages', [
        'homepage_id' => $homepage->id,
        'data' => [
            'name' => 'About Page',
            'path' => 'about-page-duplicate',
        ],
        'folder' => '/',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data.name']);

    $this->assertDatabaseCount('homepages', 2);
});
