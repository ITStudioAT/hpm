<?php

use App\Services\AdminNavigationService;
use function Pest\Laravel\getJson;

it('returns configuration data with menu', function () {
    $menu = ['dashboard'];

    $service = Mockery::mock(AdminNavigationService::class);
    $service->shouldReceive('dashboardMenu')->once()->andReturn($menu);
    app()->instance(AdminNavigationService::class, $service);

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
