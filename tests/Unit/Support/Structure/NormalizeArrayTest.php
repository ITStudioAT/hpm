<?php

use App\Support\Structure;

it('normalizes nested arrays according to the structure prototype', function () {
    $method = new ReflectionMethod(Structure::class, 'normalizeArray');
    $method->setAccessible(true);

    $listSchema = [
        [
            'id' => null,
            'is_visible' => true,
        ],
    ];

    $listInput = [
        [
            'id' => 5,
            'is_visible' => 'false',
            'other' => 'drop',
        ],
        'not-an-array',
    ];

    $listResult = $method->invoke(null, $listInput, $listSchema);

    expect($listResult)->toHaveCount(2);
    expect($listResult[0])->toMatchArray(['id' => 5, 'is_visible' => 'false']);
    expect($listResult[0])->not->toHaveKey('other');
    expect($listResult[1])->toMatchArray(['id' => null, 'is_visible' => true]);

    $assocSchema = [
        'meta' => [
            'title' => '',
            'has_nav' => true,
        ],
    ];

    $assocInput = [
        'meta' => [
            'title' => 'Docs',
            'has_nav' => '0',
            'ignored' => 'yes',
        ],
    ];

    $assocResult = $method->invoke(null, $assocInput, $assocSchema);

    expect($assocResult)->toMatchArray([
        'meta' => [
            'title' => 'Docs',
            'has_nav' => '0',
        ],
    ]);
});
