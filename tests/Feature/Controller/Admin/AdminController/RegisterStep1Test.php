<?php

use App\Models\User;
use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('creates user and sends token on register step1', function () {
    $user = User::factory()->make();

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkRegister')->once()->andReturn(null);
    $service->shouldReceive('createRegisterUser')->once()->andReturn($user);
    $service->shouldReceive('sendRegisterToken')->once()->with(1, $user, 'new@example.com');
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'REGISTER_ENTER_EMAIL',
        'email' => 'new@example.com',
    ]];

    $response = postJson('/api/admin/register_step_1', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'REGISTER_ENTER_TOKEN']);
});
