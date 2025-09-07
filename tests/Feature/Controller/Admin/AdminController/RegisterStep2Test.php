<?php

use App\Models\User;
use App\Services\AdminService;
use function Pest\Laravel\postJson;

it('verifies email and moves to field entry on register step2', function () {
    $user = User::factory()->create(['email' => 'verify@example.com', 'email_verified_at' => null]);

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkRegister')->once()->andReturn($user);
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'REGISTER_ENTER_TOKEN',
        'email' => 'verify@example.com',
        'token_2fa' => '123456',
    ]];

    $response = postJson('/api/admin/register_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'REGISTER_ENTER_FIELDS']);

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});
