<?php

declare(strict_types=1);

use App\Models\Homepage;
use App\Services\HomepageService;
use Illuminate\Support\Facades\DB;

it('creates homepage + index + header + footer and links them in structure', function () {
    $svc = new HomepageService();
    $homepage = $svc->create();

    // 4 records: homepage root + index + header + footer
    expect(Homepage::count())->toBe(4);

    // root homepage
    $root = Homepage::whereNull('homepage_id')->where('type', 'homepage')->first();
    expect($root)->not->toBeNull();
    expect($homepage->id)->toBe($root->id);

    // children
    $index  = Homepage::where('homepage_id', $root->id)->where('type', 'index')->first();
    $header = Homepage::where('homepage_id', $root->id)->where('type', 'header')->first();
    $footer = Homepage::where('homepage_id', $root->id)->where('type', 'footer')->first();

    expect($index)->not->toBeNull()
        ->and($header)->not->toBeNull()
        ->and($footer)->not->toBeNull();

    // If DB driver supports MySQL JSON path updates, the structure fields should be set
    if (DB::connection()->getDriverName() === 'mysql') {
        $root->refresh();
        $index->refresh();

        expect(data_get($root->structure, 'index.id'))->toBe($index->id);
        expect(data_get($index->structure, 'header.id'))->toBe($header->id);
        expect(data_get($index->structure, 'footer.id'))->toBe($footer->id);
    }
})->group('homepageservice', 'create');
