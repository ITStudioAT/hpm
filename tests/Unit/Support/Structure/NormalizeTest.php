<?php

use App\Support\Structure;

it('normalizes arrays to match the provided schema', function () {
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
        'footer' => [
            'id' => null,
            'is_visible' => true,
        ],
    ];

    $input = [
        'header' => [
            'id' => 10,
            'is_visible' => 'false',
            'junk' => 'ignored',
        ],
        'content' => [
            [
                'id' => 1,
                'is_visible' => 'false',
                'junk' => 'drop',
            ],
            [
                'id' => 2,
            ],
        ],
        'extra' => 'remove-me',
    ];

    $normalized = Structure::normalize($input, $schema);

    expect($normalized)->toHaveKeys(['header', 'content', 'footer']);
    expect($normalized)->not->toHaveKey('extra');
    expect($normalized['header'])->toMatchArray(['id' => 10, 'is_visible' => 'false']);
    expect($normalized['header'])->not->toHaveKey('junk');
    expect($normalized['content'])->toHaveCount(1);
    expect($normalized['content'][0])->toMatchArray(['id' => 1, 'is_visible' => 'false']);
    expect($normalized['content'][0])->not->toHaveKey('junk');
    expect($normalized['footer'])->toMatchArray(['id' => null, 'is_visible' => true]);
});
