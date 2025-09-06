<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\UserService;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\Role as AppRole;

uses()->group('userservice', 'setNewUserRoles');

test('SetNewUserRoles', function () {
    // Create roles via Spatie so assignRole/removeRole work
    $rA = SpatieRole::firstOrCreate(['name' => 'alpha', 'guard_name' => 'web']);
    $rB = SpatieRole::firstOrCreate(['name' => 'beta',  'guard_name' => 'web']);

    // Fetch their ids via your App\Models\Role (same table typically)
    $arA = AppRole::where('name', 'alpha')->firstOrFail();
    $arB = AppRole::where('name', 'beta')->firstOrFail();

    $u = User::factory()->create();
    $u->assignRole('alpha'); // start with alpha only

    $payload = [
        ['id' => $arA->id, 'role_check' => 2], // remove alpha
        ['id' => $arB->id, 'role_check' => 1], // add beta
    ];

    (new UserService())->setNewUserRoles([$u->id], $payload);

    $u->refresh();
    expect($u->hasRole('alpha'))->toBeFalse();
    expect($u->hasRole('beta'))->toBeTrue();
});
