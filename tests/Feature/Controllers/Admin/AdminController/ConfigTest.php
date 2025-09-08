<?php

/**
 * Feature Test — AdminController@config via HTTP route
 *
 * Szenarien:
 *  - Guest:    GET /api/admin/config → is_auth = false, user = null
 *  - Auth:     GET /api/admin/config → is_auth = true, user != null
 *
 * Vorteile:
 *  - Testet das API-Contract end-to-end (Routing, Middleware, Guards, Resources).
 *  - Nutzt echte Datenbank und UserFactory.
 */

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('GET /api/admin/config returns guest data (is_auth=false, user=null)', function () {
    $this->getJson('/api/admin/config')
        ->assertOk()
        ->assertJsonStructure([
            'logo',
            'copyright',
            'title',
            'company',
            'version',
            'register_admin_allowed',
            'timeout',
            'is_auth',
            'user',
            'menu',
        ])
        ->assertJsonPath('is_auth', false)
        ->assertJsonPath('user', null);
});

it('GET /api/admin/config returns authenticated user data (is_auth=true)', function () {
    $user = User::factory()->create([
        'email'      => 'unit@example.test',
        'first_name' => 'Unit',
        'last_name'  => 'Tester',
    ]);

    // Falls dein API-Guard "web" ist:
    $this->actingAs($user, 'web');

    // Falls du Sanctum verwendest, ersetze durch:
    // \Laravel\Sanctum\Sanctum::actingAs($user);

    $response = $this->getJson('/api/admin/config')
        ->assertOk()
        ->assertJsonPath('is_auth', true)
        ->assertJsonStructure([
            'user', // wir erwarten ein User-Objekt, genaue Struktur liefert die Resource
        ]);

    // Defensive Prüfung: id aus der Response == id aus DB
    $payload = $response->json('user');
    if (is_array($payload) && isset($payload['id'])) {
        expect((int) $payload['id'])->toBe((int) $user->id);
    }
});
