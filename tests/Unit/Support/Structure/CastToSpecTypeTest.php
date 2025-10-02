<?php

use App\Support\Structure;

it('casts values to match the schema primitive types', function () {
    $method = new ReflectionMethod(Structure::class, 'castToSpecType');
    $method->setAccessible(true);

    expect($method->invoke(null, '42', 0))->toBe(42);
    expect($method->invoke(null, '3.14', 0.0))->toBe(3.14);
    expect($method->invoke(null, 'false', true))->toBeFalse();
    expect($method->invoke(null, 15, ''))->toBe('15');
    $object = new stdClass();
    expect($method->invoke(null, $object, null))->toBe($object);
});
