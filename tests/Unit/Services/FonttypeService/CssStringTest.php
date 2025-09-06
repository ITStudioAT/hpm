<?php

declare(strict_types=1);

use App\Services\FonttypeService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    @mkdir(storage_path('app/private/fontsets'), 0777, true);
    Cache::flush();
});

it('returns comment for invalid slug', function () {
    $css = (new FonttypeService())->cssString('bad/slug');
    expect($css)->toContain('invalid fontset slug');
})->group('fonttype', 'css');

it('returns empty string when file missing', function () {
    $css = (new FonttypeService())->cssString('missing');
    expect($css)->toBe('');
})->group('fonttype', 'css');

it('generates CSS, memoizes in-request, and caches by mtime', function () {
    $slug = 'memotest';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode([
        'title' => ['fontWeight' => '700'],
    ]));
    clearstatcache(true, $path);

    // Stub Cache::remember to execute the callback and assert single call
    Cache::shouldReceive('remember')
        ->once()
        ->andReturnUsing(function ($key, $ttl, $callback) {
            return $callback();
        });

    $svc = new FonttypeService();
    $a = $svc->cssString($slug);
    $b = $svc->cssString($slug); // served from in-request memo, no second remember call

    expect($a)->toContain('.title {font-weight: 700;}');
    expect($b)->toBe($a);
})->group('fonttype', 'css');

it('busts cache when file mtime changes', function () {
    $slug = 'bust';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode(['title' => ['fontWeight' => '700']]));
    clearstatcache(true, $path);

    // Expect TWO Cache::remember calls (one for each distinct mtime)
    Cache::shouldReceive('remember')
        ->twice()
        ->andReturnUsing(function ($key, $ttl, $callback) {
            return $callback();
        });

    $svc = new FonttypeService();

    $first = $svc->cssString($slug);
    expect($first)->toContain('font-weight: 700;');

    // ensure mtime changes
    sleep(1);
    file_put_contents($path, json_encode(['title' => ['fontWeight' => '800']]));
    clearstatcache(true, $path);

    $second = $svc->cssString($slug);
    expect($second)->toContain('font-weight: 800;');
})->group('fonttype', 'css');

it('handles invalid JSON gracefully', function () {
    $slug = 'badjson';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, '{ "title": { "fontWeight": 700 } '); // malformed JSON
    clearstatcache(true, $path);

    $css = (new FonttypeService())->cssString($slug);

    expect($css)->toContain('invalid fontset json');
})->group('fonttype', 'css');

it('converts camelCase props to kebab-case including special map', function () {
    $slug = 'kebab';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode([
        'x' => [
            'fontVariantNumeric' => 'tabular-nums',
            'letterSpacing'      => '0.03em',
        ],
    ]));
    clearstatcache(true, $path);

    $css = (new FonttypeService())->cssString($slug);

    expect($css)->toContain('.x {font-variant-numeric: tabular-nums;letter-spacing: 0.03em;}');
})->group('fonttype', 'css');

it('sanitizes unsafe selectors to classes', function () {
    $slug = 'selector';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode([
        'bad!!selector' => ['fontSize' => '1rem'],
        'div .ok'       => ['fontWeight' => '600'], // raw selector stays
    ]));
    clearstatcache(true, $path);

    $css = (new FonttypeService())->cssString($slug);

    expect($css)->toContain('.bad--selector {font-size: 1rem;}');
    expect($css)->toContain('div .ok {font-weight: 600;}');
})->group('fonttype', 'css');

it('renders breakpoints with px for numeric keys and verbatim for units', function () {
    $slug = 'bps';
    $path = storage_path("app/private/fontsets/{$slug}.json");
    file_put_contents($path, json_encode([
        'title' => ['fontWeight' => '500'],
        'breakpoints' => [
            '768'  => ['title' => ['fontSize' => '2rem']],
            '48em' => ['title' => ['fontWeight' => '700']],
        ],
    ]));
    clearstatcache(true, $path);

    $css = (new FonttypeService())->cssString($slug);

    expect($css)->toContain('@media (min-width: 768px) {')
        ->and($css)->toContain('@media (min-width: 48em) {')
        ->and($css)->toContain('.title {font-size: 2rem;}')
        ->and($css)->toContain('.title {font-weight: 700;}');
})->group('fonttype', 'css');
