<?php

use App\Models\Homepage;

beforeEach(function () {
    $this->homepage = Homepage::factory()->create(['type' => 'index']);
    Homepage::factory()->create(['type' => 'other']);
});

function runShowHomepageTest($id)
{
    $response = test()->getJson("/api/admin/homepage/load_homepage?id={$id}");

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'id',
        'name',
        'path',
        'type',
        'structure',
    ]);

    $json = $response->json();
    expect($json['id'])->toBe($id);
}

it('assert 200 for super_admin and admin /api/admin/homepage/load_homepage', function () {
    $this->actingAs($this->super_admin, 'sanctum');
    runShowHomepageTest($this->homepage->id);

    $this->actingAs($this->admin, 'sanctum');
    runShowHomepageTest($this->homepage->id);
});

it('assert forbidden for user /api/admin/homepage/load_homepage', function () {
    $this->actingAs($this->user, 'sanctum');
    $response = test()->getJson("/api/admin/homepage/load_homepage?id={$this->homepage->id}");
    $response->assertForbidden();
});

it('assert unauthorized for guest /api/admin/homepage/load_homepage', function () {
    $response = test()->getJson("/api/admin/homepage/load_homepage?id={$this->homepage->id}");
    $response->assertUnauthorized();
});

it('assert 422 for non-existing homepage /api/admin/homepage/load_homepage', function () {
    $this->actingAs($this->admin, 'sanctum');
    $response = test()->getJson("/api/admin/homepage/load_homepage?id=999999");
    $response->assertStatus(422);
});
