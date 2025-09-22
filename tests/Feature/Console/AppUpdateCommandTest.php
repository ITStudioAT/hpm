<?php

use Illuminate\Support\Facades\Config;
use App\Models\Homepage;

// No local helper/class definitions here!
// We rely on the shared helpers loaded once in tests/Pest.php:
// - ensurePermissionTables()
// - makeUser()
// - setHomepageSchema()

beforeEach(function () {
    ensurePermissionTables();     // Spatie permission tables for initialize()
    setHomepageSchema();          // schema for updateHomepageStructures()
    makeUser();                   // first user for initialize()
    Homepage::unguard();          // convenience for test inserts
    Config::set('hpm.version', '1.2.3'); // banner text
});

/**
 * 1) Smoke test: command runs and shows start/finish lines.
 */
it('runs the update command and shows start/finish', function () {
    $this->artisan('app:update')
        ->expectsOutputToContain('🚀 Update Version 1.2.3 started.')
        ->expectsOutputToContain('✅ UpdateService finished')
        ->assertExitCode(0);
});

/**
 * 2) Normalizes legacy/outdated homepage structures into the canonical schema.
 */
it('normalizes outdated homepage structures', function () {
    $row = Homepage::query()->create([
        'type'      => 'homepage',
        'name'      => 'Home',
        'structure' => ['id' => null], // legacy flat shape
    ]);

    $this->artisan('app:update')
        ->expectsOutputToContain('🔄 Updating structure of homepage')
        ->expectsOutputToContain("🔧 Homepage #{$row->id} structure normalized.")
        ->assertExitCode(0);

    $row->refresh();

    expect($row->structure)->toMatchArray([
        'index'  => ['id' => null],
        'fonts'  => ['fontset' => 'default'],
        'colors' => ['colorset' => 'default'],
    ]);
});

/**
 * 3) When structure already matches schema, it reports "already up to date".
 */
it('prints already up to date when structure matches schema', function () {
    $expected = [
        'index'  => ['id' => null],
        'fonts'  => ['fontset' => 'default'],
        'colors' => ['colorset' => 'default'],
    ];

    $row = Homepage::query()->create([
        'type'      => 'homepage',
        'name'      => 'Home',
        'structure' => $expected,
    ]);

    // First run
    $this->artisan('app:update')
        ->expectsOutputToContain('🔄 Updating structure of homepage')
        ->expectsOutputToContain("✅ Homepage #{$row->id} already up to date.")
        ->assertExitCode(0);

    // Second run (idempotency)
    $this->artisan('app:update')
        ->expectsOutputToContain("✅ Homepage #{$row->id} already up to date.")
        ->assertExitCode(0);

    expect($row->fresh()->structure)->toMatchArray($expected);
});
