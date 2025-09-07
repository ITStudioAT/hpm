<?php

use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\Auth;

it('logs in user without 2fa', function () {
    Mockery::mock('overload:App\\Services\\AdminService')
        ->shouldReceive('checkUserLogin')
        ->once()
        ->andReturn($this->admin);

    $payload = ['data' => [
        'step' => 'LOGIN_ENTER_PASSWORD',
        'email' => $this->admin->email,
        'password' => 'secret123',
    ]];

    $response = postJson('/api/admin/login_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'LOGIN_SUCCESS', 'auth' => true])
        ->assertJsonPath('user.id', $this->admin->id);

    expect(Auth::check())->toBeTrue();
})->runInSeparateProcess();

it('requires token when 2fa enabled', function () {
    $user = $this->admin;
    $user->is_2fa = true;

    Mockery::mock('overload:App\\Services\\AdminService', function ($mock) use ($user) {
        $mock->shouldReceive('checkUserLogin')->once()->andReturn($user);
        $mock->shouldReceive('continueLoginFor2FaUser')->once()->with($user);
    });

    $payload = ['data' => [
        'step' => 'LOGIN_ENTER_PASSWORD',
        'email' => $user->email,
        'password' => 'secret123',
    ]];

    $response = postJson('/api/admin/login_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'LOGIN_ENTER_TOKEN']);

    expect(Auth::check())->toBeFalse();
})->runInSeparateProcess();
