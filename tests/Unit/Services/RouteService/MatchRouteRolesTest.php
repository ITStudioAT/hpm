<?php

declare(strict_types=1);

use App\Services\RouteService;

uses()->group('routeservice', 'match');

test('MatchRouteRoles', function () {
    $svc = new RouteService();

    $rolesMap = [
        '/alpha'        => ['a'],
        '/alpha/open'   => [],
        '/alpha/*'      => ['wild'],
        '/beta/*'       => ['b'],
    ];

    // Use reflection to call protected method
    $ref = new ReflectionClass($svc);
    $m = $ref->getMethod('matchRouteRoles');
    $m->setAccessible(true);

    // exact match wins
    $res = $m->invoke($svc, '/alpha', $rolesMap);
    expect($res)->toMatchArray(['a']);

    // exact public
    $res2 = $m->invoke($svc, '/alpha/open', $rolesMap);
    expect($res2)->toBeArray()->toBeEmpty();

    // wildcard matches root and nested
    $res3 = $m->invoke($svc, '/alpha/xyz', $rolesMap);
    expect($res3)->toMatchArray(['wild']);

    $res4 = $m->invoke($svc, '/alpha', $rolesMap);
    expect($res4)->toMatchArray(['a']); // exact preferred because exists
    // note: our implementation returns on first matching rule; since we check exact first,
    // '/alpha' returns its exact roles, not wildcard

    // unmatched -> null
    $res5 = $m->invoke($svc, '/gamma', $rolesMap);
    expect($res5)->toBeNull();
});
