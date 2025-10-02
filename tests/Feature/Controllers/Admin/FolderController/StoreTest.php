<?php

use App\Models\Folder;
use App\Models\Homepage;
use App\Services\FolderService;
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
    $response->assertJsonValidationErrors([
        'homepage_id',
        'folder_id',
        'path',
        'data.name',
    ]);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $folder = Folder::create([
        'homepage_id' => $homepage->id,
        'name' => 'page_folders',
        'type' => 'page_folders',
        'structure' => config('hpm.structures.page_folders'),
    ]);

    $response = $this->postJson('/api/admin/folders', [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/',
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

    $folder = Folder::create([
        'homepage_id' => $homepage->id,
        'name' => 'page_folders',
        'type' => 'page_folders',
        'structure' => [
            'folders' => ['/', '/Content-Folder'],
        ],
    ]);

    $response = $this->postJson('/api/admin/folders', [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/',
        'data' => ['name' => 'Content Folder'],
    ]);

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Ordner existiert bereits']);

    $folder->refresh();
    expect($folder->structure['folders'])->toBe(['/', '/Content-Folder']);
});

it('creates a folder with normalized structure for admins', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $folder = Folder::create([
        'homepage_id' => $homepage->id,
        'name' => 'page_folders',
        'type' => 'page_folders',
        'structure' => [
            'folders' => ['/', '/blog-posts'],
        ],
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/blog-posts',
        'data' => [
            'name' => 'Drafts & Updates',
        ],
    ];

    $response = $this->postJson('/api/admin/folders', $payload);

    $response->assertOk();

    $response->assertJson([
        'id' => $folder->id,
        'homepage_id' => $homepage->id,
        'type' => 'page_folders',
    ]);

    $folder->refresh();

    $expectedPath = FolderService::createPath($payload['path'], $payload['data']['name']);
    $structure = $folder->structure;

    expect($structure)->toHaveKey('folders');
    expect($structure['folders'])->toContain('/');
    expect($structure['folders'])->toContain('/blog-posts');
    expect($structure['folders'])->toContain($expectedPath);
    expect($structure['folders'])->toHaveCount(3);

    $sorted = $structure['folders'];
    $expectedSorted = $sorted;
    natcasesort($expectedSorted);
    $expectedSorted = array_values($expectedSorted);

    expect(array_values($sorted))->toEqual($expectedSorted);

    expect(Folder::withoutGlobalScopes()->find($folder->id))->not->toBeNull();
});
