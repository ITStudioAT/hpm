<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\Auth;

it('logs out authenticated user', function () {
    actingAs($this->admin);

    $response = postJson('/api/admin/execute_logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Logout successful']);

    expect(Auth::check())->toBeFalse();
});
