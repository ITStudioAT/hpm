<?php

use App\Models\Homepage;
use App\Models\Page;
use Laravel\Sanctum\Sanctum;

it('rejects unauthenticated requests when deleting a page', function () {
    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $page = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Landing',
        'path' => 'landing',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/',
    ]);

    $response = $this->deleteJson('/api/admin/pages/' . $page->id);

    $response->assertStatus(401);
    expect(Page::find($page->id))->not->toBeNull();
});

it('rejects authenticated non-admin users when deleting a page', function () {
    $user = makeUser();
    Sanctum::actingAs($user, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $page = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Landing',
        'path' => 'landing',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/',
    ]);

    $response = $this->deleteJson('/api/admin/pages/' . $page->id);

    $response->assertStatus(403);
    expect(Page::find($page->id))->not->toBeNull();
});

it('allows admins to delete pages and responds with no content', function () {
    $admin = makeUser();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $page = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'About Us',
        'path' => 'about-us',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/',
    ]);

    $response = $this->deleteJson('/api/admin/pages/' . $page->id);

    $response->assertNoContent();
    expect(Page::find($page->id))->toBeNull();
});
