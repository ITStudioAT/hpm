
<?php

use App\Models\User;
use App\Services\AdminNavigationService;


function testApiCalls()
{
    $navigationService = new AdminNavigationService();

    // menu für USERS
    $data = ['action' => 'users'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));

    $response->assertStatus(200)
        ->assertJson([
            'menu' => $navigationService->userMenu(),
            'selection' => $navigationService->userSelection(),
        ]);


    // menu für PROFILE
    $data = ['action' => 'profile'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));

    $response->assertStatus(200)
        ->assertJson([
            'menu' => $navigationService->profileMenu(),
        ]);

    // menu für HOME
    $data = ['action' => 'homepage'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));

    $response->assertStatus(200)
        ->assertJson([
            'menu' => $navigationService->homepageMenu(),
        ]);
}

it('assert 200 for super_admin and admin /api/admin/navigation/load_menu', function () {
    // super_admin
    $this->actingAs($this->super_admin, 'sanctum');
    testApiCalls();

    // admin
    $this->actingAs($this->admin, 'sanctum');
    testApiCalls();
});

it('assert 403 for user or guest  /api/admin/navigation/load_menu', function () {

    // user
    $this->actingAs($this->user, 'sanctum');
    $data = ['action' => 'users'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));
    $response->assertStatus(403);

    // guest
    $this->actingAs($this->guest, 'sanctum');
    $data = ['action' => 'users'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));
    $response->assertStatus(403);
});

it('assert 401 for unauthenticated /api/admin/navigation/load_menu', function () {

    $data = ['action' => 'users'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));
    $response->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
});

it('assert 422 for wrong action /api/admin/navigation/load_menu', function () {

    // user
    $this->actingAs($this->super_admin, 'sanctum');
    $data = ['action' => 'wrong'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));
    $response->assertStatus(422);
});
