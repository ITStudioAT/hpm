<?php
// tests/Feature/Controllers/Homepage/HomepageController/ColorSetTest.php

use Illuminate\Filesystem\Filesystem;

beforeEach(function () {
    $this->files = new Filesystem();
    $this->colorsetDir = storage_path('app/private/colorsets');

    // Ordner optional anlegen (wird NIE gelöscht)
    if (! $this->files->isDirectory($this->colorsetDir)) {
        $this->files->makeDirectory($this->colorsetDir, 0755, true);
    }

    // Liste der vom Test erzeugten Dateien (global, um Pest-Proxy-Probleme zu vermeiden)
    $GLOBALS['__colorset_created_files'] = [];
});

afterEach(function () {
    // Nur explizit vom Test erzeugte Dateien entfernen
    foreach ($GLOBALS['__colorset_created_files'] as $file) {
        if ($this->files->exists($file)) {
            $this->files->delete($file);
        }
    }
    unset($GLOBALS['__colorset_created_files']);
});

// Hilfsfunktionen
function colorsetPath(string $name): string
{
    return storage_path("app/private/colorsets/{$name}.json");
}

function writeColorset(string $name, array $payload): string
{
    $path = colorsetPath($name);
    test()->files->put($path, json_encode($payload));
    $GLOBALS['__colorset_created_files'][] = $path; // statt test()->createdFiles[]

    return $path;
}

it('returns 422 (validation) if colorset query param is missing', function () {
    // JSON-Request → FormRequest liefert 422 + Validation-Fehler
    $response = $this->getJson('/api/homepage/colorset');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['colorset']);
});

it('returns 404 if colorset file does not exist', function () {
    $name = 'does-not-exist-' . uniqid();
    $response = $this->get("/homepage/colorset?colorset={$name}");

    $response->assertStatus(404);
    // keine Body-Assertion, da Standard-Error-HTML die Message nicht garantiert enthält
});

it('returns JSON payload when colorset file exists', function () {
    $colorsetName = 'test-' . uniqid('colorset-');
    $payload = [
        'name' => 'Testset',
        'colorDefs' => [
            '--bg-all' => '#ffffff',
            '--text-all' => '#000000',
            '--color-0' => '#ffffff',
            '--color-1' => '#dddddd',
            '--color-2' => '#cccccc',
            '--color-3' => '#bbbbbb',
            '--text-0' => '#111111',
            '--text-hl' => '#000000',
            '--appbar-bg-4' => '#000000',
            '--appbar-text-4' => '#ffffff',
            '--appbar-bg-5' => '#111111',
            '--appbar-text-5' => '#ffffff',
        ],
    ];

    writeColorset($colorsetName, $payload);

    $response = $this->get("/api/homepage/colorset?colorset={$colorsetName}");

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/json');
    $response->assertExactJson($payload);
});
