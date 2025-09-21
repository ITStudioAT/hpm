<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Helper to create a unique fontset file.
 *
 * @return array{string,string} [fontsetName, filePath]
 */
function makeFontsetFile(array $data, ?string $name = null): array
{
    $dir = storage_path('app/private/fontsets');

    if (!is_dir($dir)) {
        // Create only if missing; no destructive changes.
        mkdir($dir, 0777, true);
    }

    $name ??= 'testset_' . Str::uuid()->toString();
    $path = $dir . DIRECTORY_SEPARATOR . $name . '.json';

    File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return [$name, $path];
}

beforeEach(function () {
    // Per-test tracking of files we create
    $this->createdFiles = [];

    // Ensure base directory exists
    $dir = storage_path('app/private/fontsets');
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
});

afterEach(function () {
    // Remove only files we created in this test
    if (isset($this->createdFiles) && is_array($this->createdFiles)) {
        foreach ($this->createdFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
});

it('returns 400 when the fontset query is missing', function () {
    $res = $this->get('/api/homepage/fontset');

    $res->assertStatus(400);
    // Optional (debug-only): $res->assertSeeText('Dieses Fontset wird nicht unterstützt');
});

it('returns 404 when the fontset file is missing', function () {
    $missing = 'does-not-exist-' . Str::uuid()->toString();

    $res = $this->get("/api/homepage/fontset?fontset={$missing}");

    $res->assertStatus(404);
    // Optional generic text (depends on renderer): $res->assertSeeText('Not Found');
});

it('returns 200 and JSON when the fontset file exists (with empty fonts array, so no private font() call)', function () {
    // Arrange: create a fontset file that avoids invoking the private font() method
    [$fontsetName, $path] = makeFontsetFile([
        'fonts' => [],                 // <-- critical: empty, so foreach doesn't call $this->font()
        'meta'  => ['version' => 1],
    ]);
    $this->createdFiles[] = $path;

    // Act
    $res = $this->get("/api/homepage/fontset?fontset={$fontsetName}");

    // Assert
    $res->assertOk();

    // If controller returns JSON (recommended: response()->json($data)):
    $res->assertJson([
        'fonts' => [],
        'meta'  => ['version' => 1],
    ]);

    // If controller returns a plain array response instead of JSON:
    // expect($res->original)->toMatchArray([
    //     'fonts' => [],
    //     'meta'  => ['version' => 1],
    // ]);
});
