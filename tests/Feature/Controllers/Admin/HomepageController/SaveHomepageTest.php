<?php

use App\Models\Homepage;

beforeEach(function () {
    $this->homepage = Homepage::factory()->create([
        'type' => 'index',
        'name' => 'Old Name',
    ]);
    Homepage::factory()->create(['type' => 'other']);
});

function runSaveHomepageTest($payload)
{
    $response = test()->postJson('/api/admin/homepage/save', $payload);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'id',
        'name',
        'path',
        'type',
        'structure',
    ]);

    $json = $response->json();
    expect($json['id'])->toBe($payload['homepage']['id']);
    expect($json['name'])->toBe($payload['homepage']['name']);

    // Check DB actually updated
    expect(Homepage::find($payload['homepage']['id'])->name)->toBe($payload['homepage']['name']);
}

it('assert 200 for super_admin and admin /api/admin/homepage/save', function () {
    $payload = [
        'homepage' => [
            'id' => $this->homepage->id,
            'name' => 'New Homepage Name',
            'type' => 'index',
            'path' => $this->homepage->path,
            'structure' => $this->homepage->structure,
        ]
    ];

    $this->actingAs($this->super_admin, 'sanctum');
    runSaveHomepageTest($payload);

    $this->actingAs($this->admin, 'sanctum');
    runSaveHomepageTest($payload);
});

it('assert forbidden for user /api/admin/homepage/save', function () {
    $payload = [
        'homepage' => [
            'id' => $this->homepage->id,
            'name' => 'Unauthorized Update',
            'type' => 'index',
            'path' => $this->homepage->path,
            'structure' => $this->homepage->structure,
        ]
    ];
    $this->actingAs($this->user, 'sanctum');
    $response = test()->postJson('/api/admin/homepage/save', $payload);
    $response->assertForbidden();
});

it('assert unauthorized for guest /api/admin/homepage/save', function () {
    $payload = [
        'homepage' => [
            'id' => $this->homepage->id,
            'name' => 'Guest Update',
            'type' => 'index',
            'path' => $this->homepage->path,
            'structure' => $this->homepage->structure,
        ]
    ];
    $response = test()->postJson('/api/admin/homepage/save', $payload);
    $response->assertUnauthorized();
});

it('assert 422 for non-existing homepage /api/admin/homepage/save', function () {
    $payload = [
        'homepage' => [
            'id' => 999999, // unlikely to exist
            'name' => 'Nonexistent',
            'type' => 'index',
            'path' => '/',
            'structure' => null,
        ]
    ];
    $this->actingAs($this->admin, 'sanctum');
    $response = test()->postJson('/api/admin/homepage/save', $payload);
    $response->assertStatus(422);
});
