<?php

use App\Models\User;
use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('sends reset token on password unknown step1', function () {
    $user = User::factory()->make(['email' => 'reset@example.com']);

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
    $service->shouldReceive('sendPasswordResetToken')->once()->with(1, $user, 'reset@example.com');
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_EMAIL',
        'email' => 'reset@example.com',
    ]];

    $response = postJson('/api/admin/password_unknown_step_1', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN']);
});
