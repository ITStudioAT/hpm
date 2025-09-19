<?php
// tests/Feature/Controllers/Homepage/HomepageController/ColorSetTest.php

use Illuminate\Filesystem\Filesystem;

beforeEach(function () {
    $this->files = new Filesystem();
    $this->colorsetDir = storage_path('app/private/colorsets');

    if (! $this->files->isDirectory($this->colorsetDir)) {
        $this->files->makeDirectory($this->colorsetDir, 0755, true);
    }
});

afterEach(function () {
    if ($this->files->isDirectory($this->colorsetDir)) {
        $this->files->deleteDirectory($this->colorsetDir);
    }
});

function colorsetPath(string $name): string
{
    return storage_path("app/private/colorsets/{$name}.json");
}

it('returns 400 if colorset query param is missing', function () {
    $response = $this->get('/homepage/colorset');

    $response->assertStatus(400);
    $response->assertSeeText('Dieses Colorset wird nicht unterstützt');
});

it('returns 404 if colorset file does not exist', function () {
    $response = $this->get('/homepage/colorset?colorset=does-not-exist');

    $response->assertStatus(404);
    $response->assertSeeText("Colorset 'does-not-exist' wurde nicht gefunden");
});

it('returns JSON payload when colorset file exists', function () {
    $colorsetName = 'brand-dark';
    $payload = ['primary' => '#111111', 'secondary' => '#222222'];

    $this->files->put(colorsetPath($colorsetName), json_encode($payload));

    $response = $this->get("/homepage/colorset?colorset={$colorsetName}");

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/json');
    $response->assertExactJson($payload);
});
