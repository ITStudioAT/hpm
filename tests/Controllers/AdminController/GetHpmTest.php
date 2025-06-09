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

    //  dump("SOURCE->DEST: " . $src . "->" . $dest);

    // Copy the file
    copy($src, $dest);
});


it('reads a vue file with success', function () {

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');


    // $filename = resource_path(config('hpm.pv_homepage_path') . "App.vue");

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();


    $data = $response->json();

    expect($data['hpm']['name'])->toBe("Homepage-Struktur");
    expect($data['hpm']['type'])->toBe("homepage");
});


it('reads a vue file with error, user has no needed role', function () {
    config()->set('hpm.check_spatie_role', true);
    config()->set('hpm.needed_role', ['super_admin', 'hpm_admin']);

    $user = User::find(1);
    $user->syncRoles();

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=App')
        ->assertStatus(403);

    $data = $response->json();
    expect($data['message'])->toBe("Not allowed");
});


it('reads a vue file with error, wrong file-name', function () {

    $user = User::find(1);
    Role::create(['name' => 'super_admin']);
    $user->assignRole('super_admin');

    $response = $this->actingAs($user)->getJson('/api/hpm/admin/get_hpm?source=Appx')
        ->assertStatus(500);

    $data = $response->json();
    expect($data['message'])->toBe("FILE_NOT_EXISTS");
});
