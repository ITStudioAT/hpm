<?php

use App\Services\AdminService;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\postJson;

it('logs in user without 2fa', function () {
    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkUserLogin')->once()->andReturn($this->admin);
    app()->instance(AdminService::class, $service);

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
});

it('requires token when 2fa enabled', function () {
    $user = $this->admin;
    $user->is_2fa = true;

    $service = Mockery::mock(AdminService::class);
    $service->shouldReceive('checkUserLogin')->once()->andReturn($user);
    $service->shouldReceive('continueLoginFor2FaUser')->once()->with($user);
    app()->instance(AdminService::class, $service);

    $payload = ['data' => [
        'step' => 'LOGIN_ENTER_PASSWORD',
        'email' => $user->email,
        'password' => 'secret123',
    ]];

    $response = postJson('/api/admin/login_step_2', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'LOGIN_ENTER_TOKEN']);

    expect(Auth::check())->toBeFalse();
});
