<?php

use App\Models\Homepage;
use App\Services\UpdateService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\Support\CommandFake;

// If RefreshDatabase isn't already applied globally in tests/Pest.php, uncomment:
// uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Canonical schema that your method should enforce.
    Config::set('hpm.structures', [
        'homepage' => [
            'index'  => ['id' => null],
            'fonts'  => ['fontset' => 'default'],
            'colors' => ['colorset' => 'default'],
        ],
        'landing' => [
            'hero'   => ['id' => null],
            'theme'  => ['palette' => 'light'],
        ],
    ]);

    Homepage::unguard();
});

it('normalizes a legacy flat structure to the canonical schema', function () {
    $h = Homepage::create([
        'type'      => 'homepage',
        'name'      => 'Home',
        'structure' => ['id' => null], // legacy flat shape
    ]);

    $service = new UpdateService();
    $cmd = new CommandFake();

    $service->updateHomepageStructures($cmd);
    $h->refresh();

    expect($h->structure)->toMatchArray([
        'index'  => ['id' => null],
        'fonts'  => ['fontset' => 'default'],
        'colors' => ['colorset' => 'default'],
    ]);

    expect($cmd->lines)->toContain('🔄 Updating structure of homepage');
    expect($cmd->lines)->toContain("🔧 Homepage #{$h->id} structure normalized.");
});

it('handles legacy stringified JSON in structure', function () {
    // Insert a raw JSON string directly (bypass model cast)
    $legacyJson = json_encode(['id' => null], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $id = DB::table('homepages')->insertGetId([
        'type'       => 'homepage',
        'name'       => 'Home (legacy string)',
        'structure'  => $legacyJson,   // <-- stored as TEXT/JSON string
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $h = Homepage::findOrFail($id); // model will now hydrate it (cast => array)

    $service = new UpdateService();
    $cmd = new CommandFake();

    $service->updateHomepageStructures($cmd);
    $h->refresh();

    expect($h->structure)->toMatchArray([
        'index'  => ['id' => null],
        'fonts'  => ['fontset' => 'default'],
        'colors' => ['colorset' => 'default'],
    ]);

    expect($cmd->lines)->toContain("🔧 Homepage #{$h->id} structure normalized.");
});

it('prints "already up to date" when structure already matches schema (and on subsequent runs)', function () {
    $expected = [
        'index'  => ['id' => null],
        'fonts'  => ['fontset' => 'default'],
        'colors' => ['colorset' => 'default'],
    ];

    $h = Homepage::create([
        'type'      => 'homepage',
        'name'      => 'Home',
        'structure' => $expected,
    ]);

    $service = new UpdateService();

    // First run
    $cmd1 = new CommandFake();
    $service->updateHomepageStructures($cmd1);
    expect($cmd1->lines)->toContain('🔄 Updating structure of homepage');
    expect($cmd1->lines)->toContain("✅ Homepage #{$h->id} already up to date.");

    // Second run (idempotency)
    $cmd2 = new CommandFake();
    $service->updateHomepageStructures($cmd2);
    expect($cmd2->lines)->toContain("✅ Homepage #{$h->id} already up to date.");

    expect($h->fresh()->structure)->toMatchArray($expected);
});

it('iterates all configured types and normalizes each accordingly', function () {
    $home = Homepage::create([
        'type'      => 'homepage',
        'name'      => 'Home',
        'structure' => ['id' => null],
    ]);

    $landing = Homepage::create([
        'type'      => 'landing',
        'name'      => 'Landing',
        'structure' => [
            'hero'  => ['id' => null],
            'theme' => ['palette' => 'light'],
        ],
    ]);

    $service = new UpdateService();
    $cmd = new CommandFake();

    $service->updateHomepageStructures($cmd);

    expect($home->fresh()->structure)->toMatchArray([
        'index'  => ['id' => null],
        'fonts'  => ['fontset' => 'default'],
        'colors' => ['colorset' => 'default'],
    ]);

    expect($landing->fresh()->structure)->toMatchArray([
        'hero'  => ['id' => null],
        'theme' => ['palette' => 'light'],
    ]);

    expect($cmd->lines)->toContain('🔄 Updating structure of homepage');
    expect($cmd->lines)->toContain('🔄 Updating structure of landing');
    expect(implode("\n", $cmd->lines))
        ->toContain("🔧 Homepage #{$home->id} structure normalized.")
        ->toContain("✅ Homepage #{$landing->id} already up to date.");
});
