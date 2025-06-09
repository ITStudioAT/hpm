<?php

use App\Models\User;
use function Pest\Laravel\artisan;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Source in your package
    $src = realpath(__DIR__ . '/../../../resources/js/pages/pv_homepage/App.vue');

    // Destination in the Laravel test app's resources folder
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');

    if (! $src || ! file_exists($src)) {
        test()->skip("Missing source file: $src");
    }

    // Ensure destination directory exists
    if (! file_exists(dirname($dest))) {
        mkdir(dirname($dest), 0777, true);
    }

    // Copy the file
    copy($src, $dest);
});

it('writes a vue file with success', function () {

    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $data = $response->json();

    $data['hpm']['name'] = "Homepage-Struktur changed";
    $data['hpm']['type'] = "homepage changed";

    // POST speichern
    $saveResult = $this->actingAs($user)->postJson('/api/hpm/admin/save_hpm', [
        'source' => 'App',
        'data' => $data['hpm'],
    ]);
    $saveResult->assertOk();

    $verifyResponse = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $verifyData = $verifyResponse->json();

    expect($verifyData['hpm']['name'])->toBe("Homepage-Struktur changed");
    expect($verifyData['hpm']['type'])->toBe("homepage changed");
});

it('writes a vue file with error 403, no needed role when writing', function () {

    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $data = $response->json();

    $data['hpm']['name'] = "Homepage-Struktur changed";
    $data['hpm']['type'] = "homepage changed";

    // remove roles before saving
    $user->syncRoles([]);

    $saveResult = $this->actingAs($user)->postJson('/api/hpm/admin/save_hpm', [
        'source' => 'App',
        'data' => $data['hpm'],
    ]);

    $saveResult->assertStatus(403);
});

it('writes a vue file with error 500, wrong filename', function () {

    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $data = $response->json();

    $data['hpm']['name'] = "Homepage-Struktur changed";
    $data['hpm']['type'] = "homepage changed";

    $saveResult = $this->actingAs($user)->postJson('/api/hpm/admin/save_hpm', [
        'source' => 'Appx', // invalid file name
        'data' => $data['hpm'],
    ]);

    $saveResult->assertStatus(500);
    $errorData = $saveResult->json();

    expect($errorData['message'])->toBe("Fehler beim Schreiben!");
});
