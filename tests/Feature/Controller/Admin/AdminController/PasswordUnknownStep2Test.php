<?php

use App\Models\User;
use function Pest\Laravel\postJson;

it('completes reset when user has no 2fa', function () {
    $user = User::factory()->make(['is_2fa' => false, 'email' => 'reset@example.com']);

    Mockery::mock('overload:App\\Services\\AdminService')
        ->shouldReceive('checkPasswordUnknown')
        ->once()
        ->andReturn($user);

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN',
        'email' => 'reset@example.com',
        'token_2fa' => '123456',
    ]];

    $response = postJson('/api/admin/password_unknown_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_SUCCESS']);
})->runInSeparateProcess();

it('sends second token when user has 2fa enabled', function () {
    $user = User::factory()->make(['is_2fa' => true, 'email' => 'twofa@example.com', 'email_2fa' => 'backup@example.com']);

    Mockery::mock('overload:App\\Services\\AdminService', function ($mock) use ($user) {
        $mock->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
        $mock->shouldReceive('sendPasswordResetToken')->once()->with(2, $user, 'backup@example.com');
    });

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN',
        'email' => 'twofa@example.com',
        'token_2fa' => '123456',
    ]];

    $response = postJson('/api/admin/password_unknown_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN_2']);
})->runInSeparateProcess();
