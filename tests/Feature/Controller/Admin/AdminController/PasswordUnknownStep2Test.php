<?php

use App\Models\User;
use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('completes reset when user has no 2fa', function () {
    $user = User::factory()->make(['is_2fa' => false, 'email' => 'reset@example.com']);

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN',
        'email' => 'reset@example.com',
        'token_2fa' => '123456',
    ]];

    $response = postJson('/api/admin/password_unknown_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_SUCCESS']);
});

it('sends second token when user has 2fa enabled', function () {
    $user = User::factory()->make(['is_2fa' => true, 'email' => 'twofa@example.com', 'email_2fa' => 'backup@example.com']);

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
    $service->shouldReceive('sendPasswordResetToken')->once()->with(2, $user, 'backup@example.com');
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN',
        'email' => 'twofa@example.com',
        'token_2fa' => '123456',
    ]];

    $response = postJson('/api/admin/password_unknown_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN_2']);
});
