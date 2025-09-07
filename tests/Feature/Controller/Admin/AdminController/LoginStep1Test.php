<?php

use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('prompts for password after email', function () {
    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkUserLogin')->once()->andReturn($this->admin);
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'LOGIN_ENTER_EMAIL',
        'email' => $this->admin->email,
    ]];

    $response = postJson('/api/admin/login_step_1', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'LOGIN_ENTER_PASSWORD']);
});
