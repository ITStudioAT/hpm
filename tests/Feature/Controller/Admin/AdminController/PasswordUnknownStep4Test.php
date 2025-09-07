<?php

use App\Models\User;
use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('resets password and finishes process', function () {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('setPassword')->once()->with('newpassword');

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_PASSWORD',
        'email' => 'reset@example.com',
        'token_2fa' => '123456',
        'password' => 'newpassword',
        'password_repeat' => 'newpassword',
    ]];

    $response = postJson('/api/admin/password_unknown_step_4', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_FINISHED']);
});
