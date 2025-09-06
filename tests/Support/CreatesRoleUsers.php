<?php

namespace Tests\Support;

use App\Models\User;

trait CreatesRoleUsers
{
    protected function makeUserWithRoles(array $roles = []): User
    {
        $user = User::factory()->create([
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'password' => bcrypt('secret'),
        ]);
        if (method_exists($user, 'assignRole') && $roles) {
            $user->assignRole($roles);
        }
        return $user;
    }
}
