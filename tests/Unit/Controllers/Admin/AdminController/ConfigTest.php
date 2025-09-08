<?php

/**
 * ConfigTest.php — Pest v4 Feature Test (DB-backed)
 *
 * Szenarien:
 *  - Guest:    is_auth === false, user === null
 *  - Auth:     is_auth === true,  user !== null (echter DB-User)
 *  - Struktur: alle erwarteten Keys vorhanden + Basistypen korrekt
 */

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Führt AdminController@config aus und gibt das dekodierte JSON-Array zurück.
 */
function callAdminConfig(): array
{
    $controller = app(AdminController::class);
    $response   = $controller->config(request());

    expect($response->status())->toBe(200);

    $json = $response->getData(true);

    // Immer erwartete Struktur
    expect($json)->toHaveKeys([
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
    ]);

    // Basis-Typen (API-Vertrag)
    expect($json['register_admin_allowed'])->toBeBool();
    expect($json['timeout'])->toBeInt();
    expect($json['is_auth'])->toBeBool();
    expect($json['menu'])->toBeArray();

    // Strings dürfen leer sein – aber Typen müssen passen
    expect($json['logo'])->toBeString();
    expect($json['title'])->toBeString();
    expect($json['company'])->toBeString();
    expect($json['version'])->toBeString();

    return $json;
}

it('returns the expected base structure for guests (is_auth=false, user=null)', function () {
    // Gast (kein actingAs)
    $json = callAdminConfig();

    expect($json['is_auth'])->toBeFalse();
    expect($json['user'])->toBeNull();
});

it('returns auth information and a user when authenticated (is_auth=true) using DB', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email'      => 'unit@example.test',
        'first_name' => 'Unit',
        'last_name'  => 'Tester',
    ]);

    $this->actingAs($user, 'web');

    $json = callAdminConfig();

    expect($json['is_auth'])->toBeTrue();
    expect($json['user'])->not->toBeNull();

    // Defensive ID-Prüfung (Array/Objekt möglich)
    $payloadUser = $json['user'];
    $id = is_array($payloadUser)
        ? ($payloadUser['id'] ?? null)
        : (is_object($payloadUser) ? ($payloadUser->id ?? null) : null);

    if (! is_null($id)) {
        expect((int) $id)->toBe((int) $user->id);
    }
});
