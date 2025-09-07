<?php

use App\Services\AdminService;
use App\Models\User;
use function Pest\Laravel\postJson;
use Illuminate\Support\Carbon;
use Mockery;

it('updates user details and finishes registration', function () {
    $user = User::factory()->create(['email' => 'final@example.com', 'confirmed_at' => now()]);

    Mockery::mock('overload:' . AdminService::class, function ($mock) use ($user) {
        $mock->shouldReceive('checkRegister')->once()->andReturn($user);
        $mock->shouldReceive('updateRegisterUser')->once()->andReturn($user);
    });

    $payload = ['data' => [
        'step' => 'REGISTER_ENTER_FIELDS',
        'email' => 'final@example.com',
        'token_2fa' => '123456',
        'last_name' => 'Doe',
        'first_name' => 'John',
        'password' => 'secret123',
        'password_repeat' => 'secret123',
    ]];

    $response = postJson('/api/admin/register_step_3', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'REGISTER_FINISHED']);
});
