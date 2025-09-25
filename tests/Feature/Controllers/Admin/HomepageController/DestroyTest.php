<?php

use App\Models\Homepage;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

if (! function_exists('makeActiveUserWithRole')) {
    function makeActiveUserWithRole(?string $role = null): User
    {
        $user = makeUser([
            'email' => uniqid('user_', true).'@example.test',
            'password' => bcrypt('password'),
            'is_active' => 1,
        ]);

        if ($role !== null) {
            $user->assignRole($role);
        }

        return $user;
    }
}

it('rejects unauthenticated users from deleting a homepage', function () {
    $homepage = Homepage::create([
        'name' => 'Deletable Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Delete Me']],
    ]);

    $response = $this->deleteJson("/api/admin/homepages/{$homepage->id}");

    $response->assertStatus(401);

    $this->assertDatabaseHas('homepages', [
        'id' => $homepage->id,
    ]);
});

it('rejects authenticated non-admin users from deleting a homepage', function () {
    $user = makeActiveUserWithRole();
    Sanctum::actingAs($user, ['*']);

    $homepage = Homepage::create([
        'name' => 'Deletable Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Delete Me']],
    ]);

    $response = $this->deleteJson("/api/admin/homepages/{$homepage->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('homepages', [
        'id' => $homepage->id,
    ]);
});

it('deletes a homepage when requested by an admin', function () {
    $admin = makeActiveUserWithRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $homepage = Homepage::create([
        'name' => 'Deletable Home',
        'type' => 'homepage',
        'structure' => ['index' => ['title' => 'Delete Me']],
    ]);

    $response = $this->deleteJson("/api/admin/homepages/{$homepage->id}");

    $response->assertNoContent();

    $this->assertDatabaseMissing('homepages', [
        'id' => $homepage->id,
    ]);
});
