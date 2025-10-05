<?php

use App\Models\Folder;
use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated folder updates', function () {
    $response = $this->postJson('/api/admin/folders/update', []);

    $response->assertStatus(401);
});

it('rejects non-admin users when updating folders', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/admin/folders/update', []);

    $response->assertStatus(403);
});

it('renames the requested folder path and updates dependent pages for admins', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Primary Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $folder = Folder::create([
        'homepage_id' => $homepage->id,
        'name' => 'page_folders',
        'type' => 'page_folders',
        'structure' => [
            'folders' => ['/', '/library', '/library/2024'],
        ],
    ]);

    $page = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Library Article',
        'path' => 'library-article',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/library/2024',
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/library',
        'data' => ['name' => 'Archives'],
    ];

    $response = $this->postJson('/api/admin/folders/update', $payload);

    $response->assertOk();
    $response->assertJson([
        'id' => $folder->id,
        'homepage_id' => $homepage->id,
        'type' => 'page_folders',
    ]);

    $folder->refresh();
    expect($folder->structure)->toMatchArray(config('hpm.structures.page_folders'));

    expect($page->fresh()->folder)->toBe('/Archives/2024');
});
