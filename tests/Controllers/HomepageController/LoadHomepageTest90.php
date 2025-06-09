<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\artisan;

it('load_homepage', function () {
    $response = $this->getJson('/api/hpm/homepage/load_homepage')
        ->assertOk();

    $response->assertJsonPath('homepage.0.id', 1);
    $response->assertJsonPath('homepage.0.type', 'homepage');
});
