<?php

use App\Services\AdminService;
use App\Models\User;
use function Pest\Laravel\postJson;
use Mockery;

it('resets password and finishes process', function () {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('setPassword')->once()->with('newpassword');

    Mockery::mock('overload:' . AdminService::class)
        ->shouldReceive('checkPasswordUnknown')
        ->once()
        ->andReturn($user);

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
