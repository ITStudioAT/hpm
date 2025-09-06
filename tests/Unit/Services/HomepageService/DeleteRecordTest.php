<?php

declare(strict_types=1);

use App\Models\Homepage;
use App\Services\HomepageService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/** Helper to expect abort(…) */
function expectAbort422(callable $cb, string $msgContains)
{
    try {
        $cb();
        test()->fail('Expected abort(422)');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(422);
        expect($e->getMessage())->toContain($msgContains);
    }
}

it('allows deleting an unreferenced record (returns status ok)', function () {
    $svc = new HomepageService();
    $root = $svc->create(); // creates root + index + header + footer (header/footer referenced by index)

    // Create an extra header that is NOT referenced anywhere
    $unref = Homepage::create([
        'name'        => 'Unused Header',
        'homepage_id' => $root->id,
        'path'        => '',
        'type'        => 'header',
        'structure'   => ['id' => null, 'is_visible' => true],
    ]);

    $resp = $svc->deleteRecord($unref->id);

    expect($resp->getStatusCode())->toBe(200);
    expect(Homepage::find($unref->id))->toBeNull();
})->group('homepageservice', 'deleteRecord');

it('blocks deleting a directly referenced record (index.structure.header.id)', function () {
    // This test requires MySQL JSON_EXTRACT behavior used in isReferencedInStructure()
    if (DB::connection()->getDriverName() !== 'mysql') {
        test()->markTestSkipped('MySQL required for JSON_EXTRACT guard test.');
    }

    $svc = new HomepageService();
    $root = $svc->create();

    $index  = Homepage::where('homepage_id', $root->id)->where('type', 'index')->firstOrFail();
    $header = Homepage::where('homepage_id', $root->id)->where('type', 'header')->firstOrFail();

    // Sanity: ensure reference is really stored
    $index->refresh();
    expect(data_get($index->structure, 'header.id'))->toBe($header->id);

    // Attempt to delete the referenced header
    expectAbort422(function () use ($svc, $header) {
        $svc->deleteRecord($header->id);
    }, 'wo anders verwendet');
})->group('homepageservice', 'deleteRecord');

it('blocks deleting a record referenced inside content array via JSON_TABLE', function () {
    // Requires MySQL 8 JSON_TABLE
    if (DB::connection()->getDriverName() !== 'mysql') {
        test()->markTestSkipped('MySQL required for JSON_TABLE guard test.');
    }

    $svc = new HomepageService();
    $root = $svc->create();

    // Create a new "section" record we will reference inside another record's content[]
    $section = Homepage::create([
        'name'        => 'Content Section',
        'homepage_id' => $root->id,
        'path'        => '',
        'type'        => 'section',
        'structure'   => ['id' => null],
    ]);

    // Create a sibling record whose structure.content references our $section
    $page = Homepage::create([
        'name'        => 'Page with Content',
        'homepage_id' => $root->id,
        'path'        => '/page',
        'type'        => 'index',
        'structure'   => [
            'content' => [
                ['type' => 'section', 'id' => $section->id],
                ['type' => 'header',  'id' => null],
            ],
        ],
    ]);

    // Double-check the JSON was stored
    $page->refresh();
    expect(data_get($page->structure, 'content.0.type'))->toBe('section')
        ->and(data_get($page->structure, 'content.0.id'))->toBe($section->id);

    // Deleting the referenced section should be blocked by the JSON_TABLE EXISTS query
    expectAbort422(function () use ($svc, $section) {
        $svc->deleteRecord($section->id);
    }, 'wo anders verwendet');
})->group('homepageservice', 'deleteRecord');
