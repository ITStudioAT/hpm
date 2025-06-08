<?php

use App\Models\User;
use function Pest\Laravel\artisan;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $src = realpath(__DIR__ . '/../../../resources/js/pages/pv_homepage/App.vue');
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');

    if (! $src || ! file_exists($src)) {
        $this->markTestSkipped("Missing source file: $src");
    }

    @mkdir(dirname($dest), 0777, true);
    copy($src, $dest);
});


it('reads a vue file with success', function () {

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    expect($result['hpm']['name'])->toBe("Homepage-Struktur");
    expect($result['hpm']['type'])->toBe("homepage");
});


it('reads a vue file with error, user has no needed role', function () {
    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    $user->syncRoles();

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertStatus(403);

    expect($result['message'])->toBe("Not allowed");
});


it('reads a vue file with error, wrong file-name', function () {

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=Appx')
        ->assertStatus(500);

    expect($result['message'])->toBe("Fehler beim Lesen!");
});
