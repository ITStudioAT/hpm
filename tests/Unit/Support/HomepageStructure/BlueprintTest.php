<?php

declare(strict_types=1);

use App\Support\HomepageStructure;

uses()->group('homepagestructure', 'blueprint');

beforeEach(function () {
    config()->set('hpm.structures', [
        'homepage' => [
            'index'   => ['id' => null, 'is_visible' => false],
            'content' => [],
            'meta'    => ['title' => 'Home'],
        ],
        'header' => [
            'header'  => ['id' => null, 'is_visible' => true],
        ],
        'broken' => 'not-an-array', // used to test error-case
    ]);
});

test('Blueprint', function () {
    // returns config array for known type
    $bp = HomepageStructure::blueprint('homepage');
    expect($bp)->toMatchArray([
        'index'   => ['id' => null, 'is_visible' => false],
        'content' => [],
        'meta'    => ['title' => 'Home'],
    ]);

    // throws for unknown type
    expect(fn() => HomepageStructure::blueprint('unknown'))
        ->toThrow(InvalidArgumentException::class, 'Unknown structure type: unknown');

    // throws when config entry exists but is not an array
    expect(fn() => HomepageStructure::blueprint('broken'))
        ->toThrow(InvalidArgumentException::class, 'Unknown structure type: broken');
});
