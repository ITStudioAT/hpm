<?php

use App\Models\Folder;
use App\Models\Homepage;
use App\Services\FolderService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns existing folders and creates missing ones with defaults', function () {
    $homepage = Homepage::create([
        'name' => 'Primary Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $existing = Folder::create([
        'homepage_id' => $homepage->id,
        'name' => 'page_folders',
        'type' => 'page_folders',
        'structure' => ['folders' => ['/', '/existing']],
    ]);

    $resolvedExisting = FolderService::loadOrCreateFolder($homepage->id, 'page_folders');

    expect($resolvedExisting->is($existing))->toBeTrue();
    expect(Folder::count())->toBe(1);

    $secondHomepage = Homepage::create([
        'name' => 'Secondary Homepage',
        'structure' => config('hpm.structures.homepage'),
    ]);

    $created = FolderService::loadOrCreateFolder($secondHomepage->id, 'page_folders');

    expect($created->homepage_id)->toBe($secondHomepage->id);
    expect($created->structure)->toMatchArray(config('hpm.structures.page_folders', []));
    expect(Folder::count())->toBe(2);
});
