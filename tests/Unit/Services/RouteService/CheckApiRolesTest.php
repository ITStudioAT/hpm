<?php

declare(strict_types=1);

use App\Enums\RouteResult;
use App\Models\User;
use App\Services\RouteService;
use Spatie\Permission\Models\Role;

uses()->group('routeservice', 'apiroles');

beforeEach(function () {
    foreach (['admin', 'editor', 'user', 'super_admin'] as $r) {
        Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
    }
});

function rolesMap(): array
{
    return [
        'roles' => [
            'GET /admin/users'        => ['admin'],
            'POST /admin/users'       => ['admin'],
            'GET /admin/users/:id'    => ['editor'],
            'DELETE /admin/users/:id' => ['admin'],
            '* /public/ping'          => [], // public
        ],
    ];
}

test('CheckApiRoles', function () {
    $svc = new RouteService();

    // 1) Missing 'to' -> NOT_FOUND
    $nf = $svc->checkApiRoles(null, ['method' => 'GET'], rolesMap());
    expect($nf)->toBe(RouteResult::NOT_FOUND);

    // 2) No pattern matches -> NOT_EXISTS
    $nx = $svc->checkApiRoles(null, ['to' => '/unknown', 'method' => 'GET'], rolesMap());
    expect($nx)->toBe(RouteResult::NOT_EXISTS);

    // 3) Public route -> ALLOWED for guest and user
    $guest = $svc->checkApiRoles(null, ['to' => '/public/ping', 'method' => 'PATCH'], rolesMap());
    $user  = $svc->checkApiRoles(User::factory()->create(), ['to' => '/public/ping', 'method' => 'GET'], rolesMap());
    expect($guest)->toBe(RouteResult::ALLOWED);
    expect($user)->toBe(RouteResult::ALLOWED);

    // 4) Protected route -> NOT_ALLOWED if not logged in
    $na = $svc->checkApiRoles(null, ['to' => '/admin/users', 'method' => 'GET'], rolesMap());
    expect($na)->toBe(RouteResult::NOT_ALLOWED);

    // 5) Allowed for admin (GET & POST)
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $okG = $svc->checkApiRoles($admin, ['to' => '/admin/users', 'method' => 'GET'], rolesMap());
    $okP = $svc->checkApiRoles($admin, ['to' => '/admin/users', 'method' => 'POST'], rolesMap());
    expect($okG)->toBe(RouteResult::ALLOWED);
    expect($okP)->toBe(RouteResult::ALLOWED);

    // 6) :id parameter match (editor)
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $okId = $svc->checkApiRoles($editor, ['to' => '/admin/users/42', 'method' => 'GET'], rolesMap());
    expect($okId)->toBe(RouteResult::ALLOWED);

    // 7) super_admin override
    $sa = User::factory()->create();
    $sa->assignRole('super_admin');
    $saOk = $svc->checkApiRoles($sa, ['to' => '/admin/users/999', 'method' => 'DELETE'], rolesMap());
    expect($saOk)->toBe(RouteResult::ALLOWED);

    // 8) wrong role logged-in -> NOT_ALLOWED
    $plain = User::factory()->create();
    $plain->assignRole('user');
    $nope = $svc->checkApiRoles($plain, ['to' => '/admin/users', 'method' => 'GET'], rolesMap());
    expect($nope)->toBe(RouteResult::NOT_ALLOWED);

    // 9) '/api' prefix is stripped
    $okWithApi = $svc->checkApiRoles($admin, ['to' => '/api/admin/users', 'method' => 'GET'], rolesMap());
    expect($okWithApi)->toBe(RouteResult::ALLOWED);
});
