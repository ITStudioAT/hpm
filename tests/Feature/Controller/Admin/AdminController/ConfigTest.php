<?php

use App\Services\AdminNavigationService;
use function Pest\Laravel\getJson;
use Mockery;

it('returns configuration data with menu', function () {
    $menu = ['dashboard'];

    Mockery::mock('overload:' . AdminNavigationService::class)
        ->shouldReceive('dashboardMenu')
        ->once()
        ->andReturn($menu);

    $response = getJson('/api/admin/config');

    $response->assertStatus(200)
        ->assertJson(['menu' => $menu, 'is_auth' => false])
        ->assertJsonStructure([
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
});
