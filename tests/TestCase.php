<?php

namespace Tests;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{


    public function createRoles()
    {
        foreach (['super_admin', 'admin', 'user'] as $role) {
            Role::findOrCreate($role);
        }
    }

    public function createUsers()
    {
        $users = User::factory()->count(4)->create();

        $users[0]->assignRole('super_admin');
        $users[1]->assignRole('admin');
        $users[2]->assignRole('user');

        $this->super_admin = $users[0];
        $this->admin = $users[1];
        $this->user = $users[2];
        $this->guest = $users[3];
    }
}
