<?php

declare(strict_types=1);

use App\Support\HomepageStructure;

uses()->group('homepagestructure', 'make');

beforeEach(function () {
    config()->set('hpm.structures', [
        'homepage' => [
            'index'   => ['id' => null, 'is_visible' => false],
            'content' => [],
            'meta'    => ['title' => 'Home', 'lang' => 'de'],
        ],
    ]);
});

test('Make', function () {
    // recursive overrides: only provided keys change; others remain
    $overrides = [
        'index'   => ['id' => 42], // change one nested key
        'content' => [['type' => 'header', 'id' => 7]], // replace array
        'meta'    => ['title' => 'Homepage'], // partial override
        'extra'   => ['foo' => 'bar'], // new key added
    ];

    $made = HomepageStructure::make('homepage', $overrides);

    // base keys preserved + overrides applied
    expect($made['index'])->toMatchArray(['id' => 42, 'is_visible' => false]);
    expect($made['content'])->toMatchArray([['type' => 'header', 'id' => 7]]);
    expect($made['meta'])->toMatchArray(['title' => 'Homepage', 'lang' => 'de']);
    expect($made['extra'])->toMatchArray(['foo' => 'bar']);

    // unknown type bubbles the same exception from blueprint()
    expect(fn() => HomepageStructure::make('unknown', []))
        ->toThrow(InvalidArgumentException::class, 'Unknown structure type: unknown');
});
