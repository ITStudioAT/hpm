
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
    $data = ['action' => 'home'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));

    $response->assertStatus(200)
        ->assertJson([
            'menu' => $navigationService->homeMenu(),
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
    $response->assertStatus(403)->assertJson(['message' => 'Sie haben keine Berechtigung']);

    // guest
    $this->actingAs($this->guest, 'sanctum');
    $data = ['action' => 'users'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));
    $response->assertStatus(403)->assertJson(['message' => 'Sie haben keine Berechtigung']);
});

it('assert 422 for wrong action /api/admin/navigation/load_menu', function () {

    // user
    $this->actingAs($this->user, 'sanctum');
    $data = ['action' => 'wrong'];
    $response = test()->getJson('/api/admin/navigation/load_menu?' . http_build_query($data));
    $response->assertStatus(422);
});
