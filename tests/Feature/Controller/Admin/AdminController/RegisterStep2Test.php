<?php

use App\Services\AdminService;
use App\Models\User;
use function Pest\Laravel\postJson;
use Illuminate\Support\Carbon;
use Mockery;

it('verifies email and moves to field entry on register step2', function () {
    $user = User::factory()->create(['email' => 'verify@example.com', 'email_verified_at' => null]);

    Mockery::mock('overload:' . AdminService::class)
        ->shouldReceive('checkRegister')
        ->once()
        ->andReturn($user);

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
