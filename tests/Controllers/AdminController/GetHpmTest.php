<?php

use App\Models\User;
use function Pest\Laravel\artisan;
use Spatie\Permission\Models\Role;

it('reads a vue file with success', function () {

    $src = dirname(__DIR__, 3) . '/resources/js/pages/pv_homepage/App.vue';
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');
    copy($src, $dest);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();

    expect($result['hpm']['name'])->toBe("Homepage-Struktur");
    expect($result['hpm']['type'])->toBe("homepage");
});


it('reads a vue file with error, user has no needed role', function () {
    $src = dirname(__DIR__, 3) . '/resources/js/pages/pv_homepage/App.vue';
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');
    copy($src, $dest);

    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    $user->syncRoles();

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertStatus(403);

    expect($result['message'])->toBe("Not allowed");
});


it('reads a vue file with error, wrong file-name', function () {

    $src = dirname(__DIR__, 3) . '/resources/js/pages/pv_homepage/App.vue';
    $dest = base_path('resources/vendor/hpm/js/pages/pv_homepage/App.vue');
    copy($src, $dest);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $result = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=Appx')
        ->assertStatus(500);

    expect($result['message'])->toBe("Fehler beim Lesen!");
});
