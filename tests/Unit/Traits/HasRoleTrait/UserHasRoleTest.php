<?php

declare(strict_types=1);

use App\Models\User;
use App\Traits\HasRoleTrait;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

uses()->group('traits', 'hasrole');

beforeEach(function () {
    foreach (['admin', 'user', 'editor', 'super_admin'] as $r) {
        Role::findOrCreate($r, 'web');
    }
});

/** Helper: tiny invoker class using the trait */
function hasRoleInvoker()
{
    return new class {
        use HasRoleTrait;
    };
}

test('UserHasRole', function () {
    $svc = hasRoleInvoker();

    // Guest: false
    expect($svc->userHasRole('admin'))->toBeFalse();

    // Logged-in plain user with no roles: false
    $plain = User::factory()->create();
    actingAs($plain);
    expect($svc->userHasRole('admin'))->toBeFalse();

    // Admin: true for 'admin', false for unrelated role
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    actingAs($admin);
    expect($svc->userHasRole('admin'))->toBeTrue();
    expect($svc->userHasRole('editor'))->toBeFalse();

    // Array: returns true if user has ANY of them
    expect($svc->userHasRole(['editor', 'user']))->toBeFalse(); // admin has neither
    $user = User::factory()->create();
    $user->assignRole('user');
    actingAs($user);
    expect($svc->userHasRole(['editor', 'user']))->toBeTrue();

    // super_admin override: matches any required roles due to merge with ['super_admin']
    $sa = User::factory()->create();
    $sa->assignRole('super_admin');
    actingAs($sa);
    expect($svc->userHasRole('editor'))->toBeTrue();
    expect($svc->userHasRole(['totally_unknown_role']))->toBeTrue();
});
