<?php

use App\Services\AdminService;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\Auth;
use Mockery;

it('completes login after valid token', function () {
    $user = $this->admin;
    $user->is_2fa = true;

    Mockery::mock('overload:' . AdminService::class)
        ->shouldReceive('checkUserLogin')
        ->once()
        ->andReturn($user);

    $payload = ['data' => [
        'step' => 'LOGIN_ENTER_TOKEN',
        'email' => $user->email,
        'password' => 'secret123',
        'token_2fa' => '123456',
    ]];

    $response = postJson('/api/admin/login_step_3', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'LOGIN_SUCCESS', 'auth' => true])
        ->assertJsonPath('user.id', $user->id);

    expect(Auth::check())->toBeTrue();
});
