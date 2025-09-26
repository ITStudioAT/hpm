<?php

use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

function createHomepageFixture(string $name = 'Main Homepage'): Homepage
{
    return Homepage::create([
        'name' => $name,
        'structure' => config('hpm.structures.homepage'),
    ]);
}

function createPageFixture(Homepage $homepage, array $overrides = []): Page
{
    return Page::create(array_merge([
        'homepage_id' => $homepage->id,
        'name' => 'Sample Page',
        'path' => 'sample-page',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
    ], $overrides));
}

it('rejects unauthenticated requests when updating a page', function () {
    $homepage = createHomepageFixture();
    $page = createPageFixture($homepage);

    $response = $this->putJson("/api/admin/pages/{$page->id}", [
        'id' => $page->id,
        'name' => 'Updated Page',
    ]);

    $response->assertStatus(401);

    $page->refresh();
    expect($page->name)->toBe('Sample Page');
});

it('rejects authenticated users without the admin role when updating a page', function () {
    $homepage = createHomepageFixture();
    $page = createPageFixture($homepage);

    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson("/api/admin/pages/{$page->id}", [
        'id' => $page->id,
        'name' => 'Updated Page',
    ]);

    $response->assertStatus(403);

    $page->refresh();
    expect($page->name)->toBe('Sample Page');
});

it('validates the uniqueness of the page name during update', function () {
    $homepage = createHomepageFixture();
    $page = createPageFixture($homepage, [
        'name' => 'Primary Page',
        'path' => 'primary-page',
    ]);

    createPageFixture($homepage, [
        'name' => 'Existing Page',
        'path' => 'existing-page',
    ]);

    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->putJson("/api/admin/pages/{$page->id}", [
        'id' => $page->id,
        'name' => 'Existing Page',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});

it('updates a page for admin users', function () {
    $homepage = createHomepageFixture();
    $page = createPageFixture($homepage, [
        'name' => 'Primary Page',
        'path' => 'primary-page',
        'type' => 'page',
        'structure' => [
            'header' => ['id' => 1, 'is_visible' => true],
            'content' => [],
            'footer' => ['id' => 1, 'is_visible' => true],
        ],
    ]);

    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $payload = [
        'id' => $page->id,
        'homepage_id' => $homepage->id,
        'name' => 'Updated Page',
        'path' => 'updated-page',
        'type' => 'index',
        'structure' => [
            'header' => ['id' => 77, 'is_visible' => false],
            'content' => ['blocks' => [
                ['type' => 'hero'],
            ]],
            'footer' => ['id' => 88, 'is_visible' => true],
        ],
    ];

    $response = $this->putJson("/api/admin/pages/{$page->id}", $payload);

    $response->assertOk();

    $response->assertJson([
        'id' => $page->id,
        'homepage_id' => $homepage->id,
        'name' => 'Updated Page',
        'path' => 'updated-page',
        'type' => 'index',
        'structure' => [
            'header' => ['id' => 77, 'is_visible' => false],
            'content' => ['blocks' => [
                ['type' => 'hero'],
            ]],
            'footer' => ['id' => 88, 'is_visible' => true],
        ],
    ]);

    $page->refresh();
    expect($page->name)->toBe('Updated Page');
    expect($page->path)->toBe('updated-page');
    expect($page->type)->toBe('index');
    expect($page->structure)->toMatchArray([
        'header' => ['id' => 77, 'is_visible' => false],
        'content' => ['blocks' => [
            ['type' => 'hero'],
        ]],
        'footer' => ['id' => 88, 'is_visible' => true],
    ]);
});
