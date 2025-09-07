<?php

use App\Models\User;
use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('prompts for new password after tokens', function () {
    $user = User::factory()->make(['is_2fa' => true, 'email' => 'twofa@example.com']);

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkPasswordUnknown')->once()->andReturn($user);
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'PASSWORD_UNKNOWN_ENTER_TOKEN_2',
        'email' => 'twofa@example.com',
        'token_2fa' => '123456',
        'token_2fa_2' => '654321',
    ]];

    $response = postJson('/api/admin/password_unknown_step_3', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'PASSWORD_UNKNOWN_ENTER_PASSWORD']);
});
