<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\artisan;

it('displays the version from config', function () {
    config()->set('hpm.check_spatie_role', false);

    $version = config('hpm.version');

    $response = $this->getJson('/api/hpm/admin/get_config')
        ->assertOk()
        ->assertJson([
            'version' => $version,
        ]);
});


it('returns with 403, hpm.check_spatie_role == true, but user has no valid role', function () {
    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);

    $version = config('hpm.version');

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_config')
        ->assertStatus(403);
});

it('displays the version from config, hpm.check_spatie_role == true and user has a valid role', function () {
    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $version = config('hpm.version');

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_config')
        ->assertOk()
        ->assertJson([
            'version' => $version,
        ]);
});
