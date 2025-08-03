
<?php

use App\Models\User;
use App\Services\AdminNavigationService;


function runCreateHomepageTest()
{

    $response = test()->postJson('/api/admin/homepage/create');

    // Assert: Status 200
    $response->assertStatus(200);

    // Inhalt prüfen (struktur und Werte)
    $responseData = $response->json();

    // Prüfe die genaue Struktur
    expect($responseData)->toHaveKeys([
        'id',
        'name',
        'path',
        'type',
        'structure',
    ]);

    // Werte prüfen (z. B. Standardwerte)
    expect($responseData['name'])->toStartWith('Neu ');
    expect($responseData['path'])->toBe('/');
    expect($responseData['type'])->toBe('index');
    expect($responseData['structure'])->toBeNull();

    // Typen prüfen
    expect($responseData['id'])->toBeInt();
    expect($responseData['name'])->toBeString();
}

it('assert 200 for super_admin and admin /api/admin/homepage/create', function () {
    // super_admin
    $this->actingAs($this->super_admin, 'sanctum');
    runCreateHomepageTest();

    // admin
    $this->actingAs($this->admin, 'sanctum');
    runCreateHomepageTest();
});

it('assert 403 for user or guest  /api/admin/homepage/create', function () {

    // user
    $this->actingAs($this->user, 'sanctum');
    $response = test()->postJson('/api/admin/homepage/create');
    $response->assertStatus(403);

    // guest
    $this->actingAs($this->guest, 'sanctum');
    $response = test()->postJson('/api/admin/homepage/create');
    $response->assertStatus(403);
});
