<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\artisan;

it('displays the version from config', function () {
    config()->set('hpm.check_spatie_role', false);

    $version = config('hpm.version');

    $response = $this->getJson('/api/hpm/homepage/get_config')
        ->assertOk()
        ->assertJson([
            'version' => $version,
        ]);
});
