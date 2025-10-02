<?php

use App\Models\Folder;
use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

it('moves a single page when move action is active', function () {
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
            'folders' => ['/', '/library'],
        ],
    ]);

    $target = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Library Overview',
        'path' => 'library-overview',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/library',
    ]);

    $other = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Contact',
        'path' => 'contact',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/contact',
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'move_action' => 'active',
        'page_id' => $target->id,
        'from_folder' => '/library',
        'to_folder' => '/archives',
    ];

    $response = $this->postJson('/api/admin/folders/move', $payload);

    $response->assertOk();

    expect($target->fresh()->folder)->toBe('/archives');
    expect($other->fresh()->folder)->toBe('/contact');
});

it('renames all folders for bulk moves', function () {
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
            'folders' => ['/', '/library', '/library/archive'],
        ],
    ]);

    $rootPage = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Library Root',
        'path' => 'library-root',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/library',
    ]);

    $childPage = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Library Archive',
        'path' => 'library-archive',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/library/archive',
    ]);

    $payload = [
        'homepage_id' => $homepage->id,
        'folder_id' => $folder->id,
        'move_action' => 'all',
        'page_id' => null,
        'from_folder' => '/library',
        'to_folder' => '/archives',
    ];

    $response = $this->postJson('/api/admin/folders/move', $payload);

    $response->assertOk();

    expect($rootPage->fresh()->folder)->toBe('/archives');
    expect($childPage->fresh()->folder)->toBe('/archives/archive');
});
