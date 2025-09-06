<?php

declare(strict_types=1);

use App\Models\User;
use App\Traits\HasRoleTrait;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

uses()->group('traits', 'hasrole');

beforeEach(function () {
    Role::findOrCreate('user', 'web');
});

/** Helper: tiny invoker class using the trait */
function hasRoleInvoker2()
{
    return new class {
        use HasRoleTrait;
    };
}

test('UserHasAtLeastOneRole', function () {
    $svc = hasRoleInvoker2();

    // Guest
    expect($svc->userHasAtLeastOneRole())->toBeFalse();

    // Logged-in without roles
    $u = User::factory()->create();
    actingAs($u);
    expect($svc->userHasAtLeastOneRole())->toBeFalse();

    // With a role
    $u->assignRole('user');
    expect($svc->userHasAtLeastOneRole())->toBeTrue();
});
