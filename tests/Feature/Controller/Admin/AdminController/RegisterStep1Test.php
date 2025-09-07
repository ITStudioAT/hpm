<?php

use App\Models\User;
use function Pest\Laravel\postJson;

it('creates user and sends token on register step1', function () {
    $user = User::factory()->make();

    Mockery::mock('overload:App\\Services\\AdminService', function ($mock) use ($user) {
        $mock->shouldReceive('checkRegister')->once()->andReturn(null);
        $mock->shouldReceive('createRegisterUser')->once()->andReturn($user);
        $mock->shouldReceive('sendRegisterToken')->once()->with(1, $user, 'new@example.com');
    });

    $payload = ['data' => [
        'step' => 'REGISTER_ENTER_EMAIL',
        'email' => 'new@example.com',
    ]];

    $response = postJson('/api/admin/register_step_1', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'REGISTER_ENTER_TOKEN']);
})->runInSeparateProcess();
