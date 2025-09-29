<?php

use App\Models\Folder;
use App\Models\Homepage;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated requests when creating a folder', function () {
    $response = $this->postJson('/api/admin/folders', [
        'homepage_id' => 1,
        'data' => ['name' => 'Content Folder'],
    ]);

    $response->assertStatus(401);
    $this->assertDatabaseCount('homepages', 0);
});

it('rejects authenticated users without the admin role when creating a folder', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/admin/folders', [
        'homepage_id' => 1,
        'data' => ['name' => 'Content Folder'],
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseCount('homepages', 0);
});

it('validates the required fields when storing a folder', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $response = $this->postJson('/api/admin/folders', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['homepage_id', 'data.name']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $response = $this->postJson('/api/admin/folders', [
        'homepage_id' => $homepage->id,
        'data' => ['name' => ''],
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data.name']);
});

it('rejects duplicate folder names for admins', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    Folder::create([
        'homepage_id' => $homepage->id,
        'name' => 'Content Folder',
        'path' => 'content-folder',
        'structure' => ['content' => []],
    ]);

    $response = $this->postJson('/api/admin/folders', [
        'homepage_id' => $homepage->id,
        'data' => ['name' => 'Content Folder'],
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data.name']);
});

it('creates a folder with normalized structure for admins', function () {
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
            'name' => 'Content Folder',
        ],
        'structure' => [
            'content' => [
                ['label' => 'Drafts', 'is_visible' => 'true'],
            ],
            'extra' => 'ignored',
        ],
    ];

    $response = $this->postJson('/api/admin/folders', $payload);

    $response->assertOk();

    $response->assertJson([
        'homepage_id' => $homepage->id,
        'name' => 'Content Folder',
        'path' => null,
        'type' => 'folder',
        'structure' => [
            'content' => [],
        ],
    ]);

    $folderId = $response->json('id');
    expect($folderId)->not->toBeNull();

    $this->assertDatabaseHas('homepages', [
        'id' => $folderId,
        'homepage_id' => $homepage->id,
        'name' => 'Content Folder',
        'type' => 'folder',
    ]);

    $folder = Folder::withoutGlobalScopes()->find($folderId);
    expect($folder)->not->toBeNull();
    expect($folder->structure)->toMatchArray(['content' => []]);
});
