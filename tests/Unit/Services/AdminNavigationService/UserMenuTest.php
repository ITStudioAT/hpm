<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminNavigationService;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Ensure this extra role exists even if your TestCase doesn't create it yet
    Role::findOrCreate('mediamanager_admin', 'web');
});

it('shows only Home for guests', function () {
    $menu = (new AdminNavigationService())->userMenu();

    expect($menu)->toHaveCount(1);
    expect($menu[0]['subtitle'])->toBe('Home');
    expect($menu[0]['to'])->toBe('/admin');
})->group('adminnav', 'userMenu');

it('shows only Home for plain user', function () {
    actingAs($this->user);

    $menu = (new AdminNavigationService())->userMenu();

    expect($menu)->toHaveCount(1);
    expect($menu[0]['subtitle'])->toBe('Home');
})->group('adminnav', 'userMenu');

it('shows only Home for admin (no super_admin)', function () {
    actingAs($this->admin);

    $menu = (new AdminNavigationService())->userMenu();

    expect($menu)->toHaveCount(1);
    expect($menu[0]['subtitle'])->toBe('Home');
})->group('adminnav', 'userMenu');

it('shows Home + Rollen for super_admin', function () {
    actingAs($this->super_admin);

    $menu = (new AdminNavigationService())->userMenu();

    expect($menu)->toHaveCount(2);

    // first: Home
    expect($menu[0]['subtitle'])->toBe('Home')
        ->and($menu[0]['icon'])->toBe('mdi-home')
        ->and($menu[0]['to'])->toBe('/admin');

    // second: Rollen
    expect($menu[1]['subtitle'])->toBe('Rollen')
        ->and($menu[1]['icon'])->toBe('mdi-badge-account-horizontal-outline')
        ->and($menu[1]['to'])->toBe('/admin/users/roles');
})->group('adminnav', 'userMenu');

it('shows only Home for mediamanager_admin only', function () {
    $mm = User::factory()->create();
    $mm->assignRole('mediamanager_admin');
    actingAs($mm);

    $menu = (new AdminNavigationService())->userMenu();

    expect($menu)->toHaveCount(1);
    expect($menu[0]['subtitle'])->toBe('Home');
})->group('adminnav', 'userMenu');

it('shows Rollen when user has admin + super_admin (super_admin overrides)', function () {
    $u = User::factory()->create();
    $u->assignRole('admin', 'super_admin');
    actingAs($u);

    $menu  = (new AdminNavigationService())->userMenu();
    $subs  = array_column($menu, 'subtitle');

    expect($subs)->toContain('Home', 'Rollen');
})->group('adminnav', 'userMenu');
