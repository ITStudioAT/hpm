<?php

declare(strict_types=1);

use App\Services\FonttypeService;

beforeEach(function () {
    // ensure dir exists for fontsets
    @mkdir(storage_path('app/private/fontsets'), 0777, true);

    // clear conditional request headers between tests
    request()->headers->remove('If-None-Match');
    request()->headers->remove('If-Modified-Since');
});

it('returns 404 for invalid slug', function () {
    $res = (new FonttypeService())->serve('bad/slug');
    expect($res->status())->toBe(404);
    expect($res->headers->get('Content-Type'))->toBe('text/css; charset=UTF-8');
    expect($res->getContent())->toContain('fontset slug invalid');
})->group('fonttype', 'serve');

it('returns 404 when fontset file is missing', function () {
    $res = (new FonttypeService())->serve('doesnotexist');
    expect($res->status())->toBe(404);
    expect($res->getContent())->toContain('fontset not found: doesnotexist');
})->group('fonttype', 'serve');

it('returns 200 with CSS and proper cache headers', function () {
    $slug = 'testset';
    $path = storage_path("app/private/fontsets/{$slug}.json");

    $cfg = [
        '@font-face' => [[
            'fontFamily'   => "Inter",
            'src'          => "url('/fonts/inter.woff2') format('woff2')",
            'fontWeight'   => '400',
        ]],
        'heroTitle' => [
            'fontFamily'          => 'Inter',
            'fontWeight'          => '700',
            'fontSize'            => 'clamp(2rem,5vw,3rem)',
            'fontVariantNumeric'  => 'tabular-nums',
        ],
        'h1, h2' => [
            'letterSpacing' => '0.02em',
        ],
        'bad!!class' => [
            'fontSize' => '1rem',
        ],
        'breakpoints' => [
            '768'  => ['heroTitle' => ['fontSize' => '3.2rem']],
            '48em' => ['heroTitle' => ['fontWeight' => '800']],
        ],
    ];

    file_put_contents($path, json_encode($cfg, JSON_UNESCAPED_SLASHES));
    clearstatcache(true, $path);
    $mtime = filemtime($path);
    $expectedEtag = 'W/"fontset-' . $slug . '-' . $mtime . '"';
    $expectedLM   = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

    $res = (new FonttypeService())->serve($slug);

    expect($res->status())->toBe(200);
    expect($res->headers->get('Content-Type'))->toBe('text/css; charset=UTF-8');
    expect($res->headers->get('Cache-Control'))->toContain('immutable');
    expect($res->headers->get('ETag'))->toBe($expectedEtag);
    expect($res->headers->get('Last-Modified'))->toBe($expectedLM);
    expect($res->headers->get('Vary'))->toBe('Accept-Encoding');

    $css = $res->getContent();
    expect($css)->toContain('.heroTitle {')
        ->and($css)->toContain('font-family: Inter;')
        ->and($css)->toContain('font-weight: 700;')
        ->and($css)->toContain('font-size: clamp(2rem,5vw,3rem);')
        ->and($css)->toContain('font-variant-numeric: tabular-nums;')
        ->and($css)->toContain('h1, h2 {letter-spacing: 0.02em;}')
        ->and($css)->toContain('.bad--class {font-size: 1rem;}')
        ->and($css)->toContain("@font-face {font-family: Inter;src: url('/fonts/inter.woff2') format('woff2');font-weight: 400;}")
        ->and($css)->toContain('@media (min-width: 768px)')
        ->and($css)->toContain('@media (min-width: 48em)');
})->group('fonttype', 'serve');

it('returns 304 Not Modified when If-None-Match matches', function () {
    $slug = 'etagset';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode(['heroTitle' => ['fontWeight' => '600']]));
    clearstatcache(true, $path);
    $mtime = filemtime($path);
    $etag  = 'W/"fontset-' . $slug . '-' . $mtime . '"';
    $lm    = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

    request()->headers->set('If-None-Match', $etag);

    $res = (new FonttypeService())->serve($slug);

    expect($res->status())->toBe(304);
    expect($res->headers->get('ETag'))->toBe($etag);
    expect($res->headers->get('Last-Modified'))->toBe($lm);
    expect($res->headers->get('Cache-Control'))->toContain('immutable');
})->group('fonttype', 'serve');

it('returns 304 Not Modified when If-Modified-Since matches', function () {
    $slug = 'imsset';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode(['heroTitle' => ['fontWeight' => '500']]));
    clearstatcache(true, $path);
    $mtime = filemtime($path);
    $lm    = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

    request()->headers->remove('If-None-Match');
    request()->headers->set('If-Modified-Since', $lm);

    $res = (new FonttypeService())->serve($slug);

    expect($res->status())->toBe(304);
    expect($res->headers->get('Last-Modified'))->toBe($lm);
})->group('fonttype', 'serve');
