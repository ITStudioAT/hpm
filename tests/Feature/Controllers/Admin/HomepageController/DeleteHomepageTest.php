
<?php

use App\Models\User;
use App\Services\HomepageService;
use App\Services\AdminNavigationService;


function createHomepage()
{
    $homepageService = new HomepageService();
    $homepage = $homepageService->create();
    return $homepage->id;
}



function runDeleteHomepageTest($id)
{
    $response = test()->postJson('/api/admin/homepage/delete', [
        'id' => $id,
    ]);

    // Assert: Status 200
    $response->assertNoContent();
}

it('assert 200 for super_admin and admin /api/admin/homepage/delete', function () {
    // super_admin
    $homepageId = createHomepage();
    $this->actingAs($this->super_admin, 'sanctum');
    runDeleteHomepageTest($homepageId);

    $homepageId = createHomepage();
    $this->actingAs($this->admin, 'sanctum');
    runDeleteHomepageTest($homepageId);
});


it('assert 403 for user or guest  /api/admin/homepage/delete', function () {

    // user
    $this->actingAs($this->user, 'sanctum');
    $homepageId = createHomepage();
    $response = test()->postJson('/api/admin/homepage/delete', [
        'id' => $homepageId,
    ]);
    $response->assertStatus(403);

    // guest
    $this->actingAs($this->guest, 'sanctum');
    $homepageId = createHomepage();
    $response = test()->postJson('/api/admin/homepage/delete', [
        'id' => $homepageId,
    ]);
    $response->assertStatus(403);
});

it('assert 422 for wrong id  /api/admin/homepage/delete', function () {

    // user
    $this->actingAs($this->super_admin, 'sanctum');
    $homepageId = createHomepage();
    $response = test()->postJson('/api/admin/homepage/delete', [
        'id' => 999,
    ]);
    $response->assertStatus(422);
});
