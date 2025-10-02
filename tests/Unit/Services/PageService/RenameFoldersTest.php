<?php

use App\Models\Homepage;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renames matching page folders and their descendants', function () {
    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $root = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Library Overview',
        'path' => 'library-overview',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/library',
    ]);

    $nested = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Library 2024',
        'path' => 'library-2024',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/library/2024',
    ]);

    $other = Page::create([
        'homepage_id' => $homepage->id,
        'name' => 'Contact',
        'path' => 'contact',
        'type' => 'page',
        'structure' => config('hpm.structures.page'),
        'folder' => '/other',
    ]);

    PageService::renameFolders($homepage->id, '/library', '/archives');

    expect($root->fresh()->folder)->toBe('/archives');
    expect($nested->fresh()->folder)->toBe('/archives/2024');
    expect($other->fresh()->folder)->toBe('/other');
});
