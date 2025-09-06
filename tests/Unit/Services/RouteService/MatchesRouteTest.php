<?php

declare(strict_types=1);

use App\Services\RouteService;

uses()->group('routeservice', 'match');

test('MatchesRoute', function () {
    $ref = new ReflectionClass(RouteService::class);
    $m = $ref->getMethod('matchesRoute');
    $m->setAccessible(true);

    // exact method & path
    expect($m->invoke(null, 'GET /admin/users', 'GET /admin/users'))->toBeTrue();

    // method wildcard '*'
    expect($m->invoke(null, '* /public/ping', 'PATCH /public/ping'))->toBeTrue();

    // path with :id wildcard
    expect($m->invoke(null, 'GET /admin/users/:id', 'GET /admin/users/42'))->toBeTrue();

    // method mismatch (no '*')
    expect($m->invoke(null, 'POST /admin/users', 'GET /admin/users'))->toBeFalse();

    // structure malformed -> false
    expect($m->invoke(null, 'INVALID', 'GET /x'))->toBeFalse();
});
