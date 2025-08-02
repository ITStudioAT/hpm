<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class InstallUpdateService
{
    public function createRoles($roles)
    {
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function makeUser_1_toSuperAdmin()
    {
        $user = User::find(1);
        $user->assignRole('super_admin');
    }
}
