<?php

use App\Services\UpdateService;

it('detects associative arrays', function () {
    $service = new UpdateService();

    $method = new ReflectionMethod(UpdateService::class, 'isAssoc');
    $method->setAccessible(true);

    expect($method->invoke($service, ['foo' => 'bar']))->toBeTrue();
    expect($method->invoke($service, ['alpha', 'beta']))->toBeFalse();
});
