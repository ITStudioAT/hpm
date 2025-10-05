<?php

use App\Models\Folder;
use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

it('removes the folder entry from the structure for admins', function () {
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
            'folders' => ['/', '/drafts'],
        ],
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/drafts',
    ];

    $response = $this->postJson('/api/admin/folders/destroy', $payload);

    $response->assertOk();

    $folder->refresh();
    expect($folder->structure['folders'])->toBe(['/']);
});

it('deletes folders even when subfolders exist', function () {
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
            'folders' => ['/', '/drafts', '/drafts/subfolder'],
        ],
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/drafts',
    ];

    $response = $this->postJson('/api/admin/folders/destroy', $payload);

    $response->assertOk();

    $folder->refresh();
    expect($folder->structure['folders'])->toBe(['/']);
});

it('prevents deleting folders that still contain pages', function () {
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
            'folders' => ['/', '/drafts'],
        ],
    ]);

    Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Draft Page',
        'path' => 'draft-page',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/drafts',
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'path' => '/drafts',
    ];

    $response = $this->postJson('/api/admin/folders/destroy', $payload);

    $response->assertStatus(409);
    $response->assertJson(['message' => 'Der Ordner beinhaltet noch Dateien.']);

    $folder->refresh();
    expect($folder->structure['folders'])->toBe(['/', '/drafts']);
});
