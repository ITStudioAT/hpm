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

    $response = $this->actingAs($user)->get('/api/hpm/admin/get_hpm?source=App')
        ->assertOk();


    dump($response);
    $data = $response->json();
});
