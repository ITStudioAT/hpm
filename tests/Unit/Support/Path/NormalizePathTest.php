<?php

use App\Support\Path;

it('normalizes raw folder paths into a safe slug', function (string $raw, string $expected) {
    expect(Path::normalizePath($raw))->toBe($expected);
})->with([
    'trim spaces and punctuation' => ['  /About Team?!  ', 'About-Team'],
    'collapse repeated separators' => ['news///latest', 'news///latest'],
    'replace disallowed characters' => ['docs & files', 'docs-files'],
    'remove trailing separators' => ['section/sub-section/', 'section/sub-section'],
    'empty input stays empty' => ['', ''],
]);
