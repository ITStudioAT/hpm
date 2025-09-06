<?php

declare(strict_types=1);

use App\Services\RouteService;

uses()->group('routeservice', 'match');

test('MatchesPath', function () {
    $ref = new ReflectionClass(RouteService::class);
    $m = $ref->getMethod('matchesPath');
    $m->setAccessible(true);

    // exact segments
    expect($m->invoke(null, '/admin/users', '/admin/users'))->toBeTrue();

    // :id wildcard matches any single segment
    expect($m->invoke(null, '/admin/users/:id', '/admin/users/7'))->toBeTrue();

    // multiple wildcards
    expect($m->invoke(null, '/shop/:cat/:sku', '/shop/books/ABC-123'))->toBeTrue();

    // different segment count -> false
    expect($m->invoke(null, '/a/b', '/a/b/c'))->toBeFalse();

    // mismatch literal -> false
    expect($m->invoke(null, '/admin/users', '/admin/posts'))->toBeFalse();
});
