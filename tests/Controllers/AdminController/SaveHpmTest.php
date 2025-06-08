<?php

use App\Models\User;
use function Pest\Laravel\artisan;
use Spatie\Permission\Models\Role;

it('writes a vue file with success', function () {
    $src = dirname(__DIR__, 3) . '/resources/js/pages/pv_homepage/App.vue';
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');
    copy($src, $dest);


    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $data = $result->json();

    $data['hpm']['name'] = "Homepage-Struktur changed";
    $data['hpm']['type'] = "homepage changed";

    // POST speichern
    $saveResult = $this->actingAs($user)->postJson('/api/hpm/admin/save_hpm', [
        'source' => 'App',
        'data' => $data['hpm'],
    ]);

    $saveResult->assertOk();

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    expect($result['hpm']['name'])->toBe("Homepage-Struktur changed");
    expect($result['hpm']['type'])->toBe("homepage changed");
});


it('writes a vue file with error 403, no needed role when writing', function () {
    $src = dirname(__DIR__, 3) . '/resources/js/pages/pv_homepage/App.vue';
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');
    copy($src, $dest);


    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $data = $result->json();

    $data['hpm']['name'] = "Homepage-Struktur changed";
    $data['hpm']['type'] = "homepage changed";

    $user->syncRoles([]);

    // POST speichern
    $saveResult = $this->actingAs($user)->postJson('/api/hpm/admin/save_hpm', [
        'source' => 'App',
        'data' => $data['hpm'],
    ]);

    $saveResult->assertStatus(403);
});

it('writes a vue file with error 500, wrong filename', function () {
    $src = dirname(__DIR__, 3) . '/resources/js/pages/pv_homepage/App.vue';
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');
    copy($src, $dest);


    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    $data = $result->json();

    $data['hpm']['name'] = "Homepage-Struktur changed";
    $data['hpm']['type'] = "homepage changed";

    // POST speichern
    $saveResult = $this->actingAs($user)->postJson('/api/hpm/admin/save_hpm', [
        'source' => 'Appx',
        'data' => $data['hpm'],
    ]);

    $saveResult->assertStatus(500);
    expect($saveResult['message'])->toBe("Fehler beim Schreiben!");
});
