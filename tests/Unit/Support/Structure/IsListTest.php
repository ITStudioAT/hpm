<?php

use App\Support\Structure;

it('detects list-style arrays', function () {
    $method = new ReflectionMethod(Structure::class, 'isList');
    $method->setAccessible(true);

    expect($method->invoke(null, ['a', 'b', 'c']))->toBeTrue();
    expect($method->invoke(null, ['first' => 'a', 'second' => 'b']))->toBeFalse();
    expect($method->invoke(null, []))->toBeFalse();
});
