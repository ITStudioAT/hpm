<?php

use App\Models\User;
use function Pest\Laravel\postJson;

it('sends reset token on password unknown step1', function () {
    $user = User::factory()->make(['email' => 'reset@example.com']);

    Mockery::mock('overload:App\\Services\\AdminService', function ($mock) use ($user) {
        $mock->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
        $mock->shouldReceive('sendPasswordResetToken')->once()->with(1, $user, 'reset@example.com');
    });

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_EMAIL',
        'email' => 'reset@example.com',
    ]];

    $response = postJson('/api/admin/password_unknown_step_1', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN']);
})->runInSeparateProcess();
