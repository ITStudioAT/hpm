<?php

use App\Services\UpdateService;

it('normalizes nested structures according to a schema prototype', function () {
    $schema = [
        'header' => [
            'id' => null,
            'is_visible' => true,
        ],
        'content' => [
            [
                'id' => null,
                'is_visible' => true,
            ],
        ],
    ];

    $current = [
        'header' => [
            'id' => 42,
            'is_visible' => false,
            'junk' => 'remove',
        ],
        'content' => [
            [
                'id' => 7,
                'is_visible' => false,
                'extra' => 'ignore',
            ],
            'not-an-array',
        ],
        'unused' => 'value',
    ];

    $service = new UpdateService();

    $method = new ReflectionMethod(UpdateService::class, 'normalizeStructure');
    $method->setAccessible(true);

    $normalized = $method->invoke($service, $current, $schema);

    expect($normalized['header'])->toMatchArray(['id' => 42, 'is_visible' => false]);
    expect($normalized['content'])->toHaveCount(2);
    expect($normalized['content'][0])->toMatchArray(['id' => 7, 'is_visible' => false]);
    expect($normalized['content'][1])->toMatchArray(['id' => null, 'is_visible' => true]);
    expect($normalized)->not->toHaveKey('unused');
});
