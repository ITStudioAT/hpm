<?php

use Illuminate\Support\Facades\File;

it('returns the real colorset and fontset names from storage', function () {
    $colorsetPath = storage_path('app/private/colorsets');
    $fontsetPath  = storage_path('app/private/fontsets');

    // If a dir is missing in a fresh env, skip rather than fail
    if (!is_dir($colorsetPath) || !is_dir($fontsetPath)) {
        $this->markTestSkipped('Colorset or fontset directory does not exist.');
    }

    // Build expected lists from disk (json files only), order-insensitive
    $expectedColors = collect(File::files($colorsetPath))
        ->filter(fn($f) => strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION)) === 'json')
        ->map(fn($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))
        ->sort()
        ->values()
        ->all();

    $expectedFonts = collect(File::files($fontsetPath))
        ->filter(fn($f) => strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION)) === 'json')
        ->map(fn($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))
        ->sort()
        ->values()
        ->all();

    // Hit the route
    $response = $this->getJson('/api/homepage/load_sets');

    // Basic shape
    $response->assertOk()->assertJsonStructure(['colorsets', 'fontsets']);

    // Compare ignoring order
    $actualColors = collect($response->json('colorsets'))->sort()->values()->all();
    $actualFonts  = collect($response->json('fontsets'))->sort()->values()->all();

    $this->assertEquals($expectedColors, $actualColors, 'Colorsets mismatch with storage files.');
    $this->assertEquals($expectedFonts,  $actualFonts,  'Fontsets mismatch with storage files.');
});
