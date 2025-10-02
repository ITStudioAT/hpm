<?php

use App\Models\Folder;
use App\Models\Homepage;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated users when listing folders', function () {
    $response = $this->getJson('/api/admin/folders?homepage_id=1&type=page_folders');

    $response->assertStatus(401);
});

it('rejects non-admin users when listing folders', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson('/api/admin/folders?homepage_id=1&type=page_folders');

    $response->assertStatus(403);
});

it('returns the folder resource for admins and creates it when missing', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Primary Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $response = $this->getJson('/api/admin/folders?homepage_id=' . $homepage->id . '&type=page_folders');

    $response->assertOk();
    $response->assertJson([
        'homepage_id' => $homepage->id,
        'type' => 'page_folders',
    ]);

    expect(Folder::count())->toBe(1);
    $folder = Folder::first();
    expect($folder->homepage_id)->toBe($homepage->id);
    expect($folder->structure)->toMatchArray(config('hpm.structures.page_folders'));
});
