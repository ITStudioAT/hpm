<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminNavigationService;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    // ensure role exists for negative case
    Role::findOrCreate('mediamanager_admin', 'web');
});

it('returns empty homepage menu for guests', function () {
    $menu = (new AdminNavigationService())->homepageMenu();
    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'homepage');

it('returns one Home entry for admin', function () {
    actingAs($this->admin);

    $menu = (new AdminNavigationService())->homepageMenu();

    expect($menu)->toHaveCount(1);
    $item = $menu[0];

    expect($item['subtitle'])->toBe('Home')
        ->and($item['icon'])->toBe('mdi-home')
        ->and($item['color'])->toBe('secondary')
        ->and($item['to'])->toBe('/admin');
})->group('adminnav', 'homepage');

it('returns empty for plain user', function () {
    actingAs($this->user);

    $menu = (new AdminNavigationService())->homepageMenu();
    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'homepage');

it('returns empty for mediamanager_admin only', function () {
    /** @var User $mm */
    $mm = User::factory()->create();
    $mm->assignRole('mediamanager_admin');
    actingAs($mm);

    $menu = (new AdminNavigationService())->homepageMenu();
    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'homepage');

it('returns one Home entry for super_admin (override)', function () {
    actingAs($this->super_admin);

    $menu = (new AdminNavigationService())->homepageMenu();
    expect($menu)->toHaveCount(1)
        ->and($menu[0]['subtitle'])->toBe('Home');
})->group('adminnav', 'homepage');

it('still returns a single Home entry when user has admin + mediamanager_admin', function () {
    $u = User::factory()->create();
    $u->assignRole('admin', 'mediamanager_admin');

    actingAs($u);

    $menu = (new AdminNavigationService())->homepageMenu();
    expect($menu)->toHaveCount(1)
        ->and($menu[0]['subtitle'])->toBe('Home');
})->group('adminnav', 'homepage');
