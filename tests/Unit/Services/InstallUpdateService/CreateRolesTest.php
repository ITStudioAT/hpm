<?php

declare(strict_types=1);

use App\Services\InstallUpdateService;
use Spatie\Permission\Models\Role;

uses()->group('installupdate', 'createRoles');

it('creates new roles and is idempotent', function () {
    $svc = new InstallUpdateService();

    $roles = ['install_role_a', 'install_role_b'];

    expect(Role::whereIn('name', $roles)->count())->toBe(0);

    $svc->createRoles($roles);
    expect(Role::whereIn('name', $roles)->pluck('name')->all())
        ->toMatchArray($roles);

    $countAfterFirst = Role::whereIn('name', $roles)->count();

    $svc->createRoles($roles);
    $countAfterSecond = Role::whereIn('name', $roles)->count();

    expect($countAfterSecond)->toBe($countAfterFirst);

    $guards = Role::whereIn('name', $roles)->pluck('guard_name')->unique()->values()->all();
    expect($guards)->toContain('web'); // adjust if your default guard differs
});
