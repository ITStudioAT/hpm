<?php

use function Pest\Laravel\postJson;

it('prompts for password after email', function () {
    Mockery::mock('overload:App\\Services\\AdminService')
        ->shouldReceive('checkUserLogin')
        ->once()
        ->andReturn($this->admin);

    $payload = ['data' => [
        'step' => 'LOGIN_ENTER_EMAIL',
        'email' => $this->admin->email,
    ]];

    $response = postJson('/api/admin/login_step_1', $payload);

    $response->assertStatus(200)
        ->assertJson(['step' => 'LOGIN_ENTER_PASSWORD']);
})->runInSeparateProcess();
