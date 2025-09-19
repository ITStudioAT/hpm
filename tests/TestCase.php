<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Rollen anlegen (guard_name = web, falls du etwas anderes nutzt, bitte anpassen)
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('super_admin', 'web');
    }

    protected function createUserWithRole(string $role): User
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    protected function actingAsRole(string $role): User
    {
        $user = $this->createUserWithRole($role);
        $this->actingAs($user);

        return $user;
    }
}
