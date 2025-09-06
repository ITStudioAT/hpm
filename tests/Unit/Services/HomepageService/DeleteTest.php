<?php

declare(strict_types=1);

use App\Models\Homepage;
use App\Services\HomepageService;

it('deletes a homepage and all its child records', function () {
    $svc = new HomepageService();
    $homepage = $svc->create();

    $rootId = $homepage->id;
    $childIds = Homepage::where('homepage_id', $rootId)->pluck('id')->all();

    expect(Homepage::where('homepage_id', $rootId)->count())->toBe(3); // index, header, footer

    $svc->delete($rootId);

    expect(Homepage::find($rootId))->toBeNull();
    expect(Homepage::whereIn('id', $childIds)->count())->toBe(0);
})->group('homepageservice', 'delete');
