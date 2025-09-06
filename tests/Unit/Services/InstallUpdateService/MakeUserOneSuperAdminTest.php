<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\InstallUpdateService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

uses()->group('installupdate', 'makeUser1');

beforeEach(function () {
    // Ensure role exists
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    // Ensure there is a user with ID = 1 (auto-increment may have drifted)
    if (! User::find(1)) {
        DB::table('users')->insert([
            'id'         => 1,
            'first_name' => 'Alpha',
            'last_name'  => 'One',
            'email'      => 'user1+' . uniqid() . '@test.tld',
            'password'   => bcrypt('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Clear any existing roles so we test assignment cleanly
    User::find(1)->syncRoles([]);
});

it('assigns super_admin to user with id 1', function () {
    $svc = new InstallUpdateService();

    $user = User::findOrFail(1);
    expect($user->hasRole('super_admin'))->toBeFalse();

    $svc->makeUser_1_toSuperAdmin();

    $user->refresh();
    expect($user->hasRole('super_admin'))->toBeTrue();
    expect($user->roles()->where('name', 'super_admin')->count())->toBe(1);
});

it('is idempotent when called multiple times', function () {
    $svc = new InstallUpdateService();

    $svc->makeUser_1_toSuperAdmin();
    $svc->makeUser_1_toSuperAdmin();

    $user = User::findOrFail(1);
    expect($user->hasRole('super_admin'))->toBeTrue();
    expect($user->roles()->where('name', 'super_admin')->count())->toBe(1);
});
