<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication; // <- important for Laravel to boot

    /** @var User */
    protected $super_admin;
    /** @var User */
    protected $admin;
    /** @var User */
    protected $user;
    /** @var User */
    protected $guest;

    public function createRoles(): void
    {
        // include every role you actually use in services/tests
        foreach (['super_admin', 'admin', 'user', 'mediamanager_admin'] as $role) {
            // specify guard to avoid surprises
            Role::findOrCreate($role, 'web');
        }
    }

    public function createUsers(): void
    {
        // ensure your UserFactory sets first_name/last_name or override here
        $users = User::factory()
            ->count(4)
            ->create();

        $users[0]->assignRole('super_admin');
        $users[1]->assignRole('admin');
        $users[2]->assignRole('user');

        $this->super_admin = $users[0];
        $this->admin       = $users[1];
        $this->user        = $users[2];
        $this->guest       = $users[3];
    }
}
