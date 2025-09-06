<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminNavigationService;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Ensure the role used in a negative case exists
    Role::findOrCreate('mediamanager_admin', 'web');
});

it('returns empty profile menu for guests', function () {
    $menu = (new AdminNavigationService())->profileMenu();
    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'profile');

it('returns 3 entries for admin', function () {
    actingAs($this->admin);

    $menu = (new AdminNavigationService())->profileMenu();

    expect($menu)->toHaveCount(3);

    $subs  = array_column($menu, 'subtitle');
    $icons = array_column($menu, 'icon');

    expect($subs)->toContain('Home', 'Kennwort ändern', '2-FA-Authentifizierung');
    expect($icons)->toContain('mdi-home', 'mdi-form-textbox-password', 'mdi-two-factor-authentication');

    // Check routing/actions explicitly
    $home = $menu[0];
    expect($home['to'] ?? null)->toBe('/admin');

    $pwd = $menu[1];
    expect($pwd['action'] ?? null)->toBe('wantToChangePassword');

    $twofa = $menu[2];
    expect($twofa['action'] ?? null)->toBe('wantToChange2Fa');
})->group('adminnav', 'profile');

it('returns 3 entries for user', function () {
    actingAs($this->user);

    $menu = (new AdminNavigationService())->profileMenu();

    expect($menu)->toHaveCount(3);
    expect(array_column($menu, 'subtitle'))
        ->toContain('Home', 'Kennwort ändern', '2-FA-Authentifizierung');
})->group('adminnav', 'profile');

it('returns 3 entries for super_admin (override: gets everything)', function () {
    actingAs($this->super_admin);

    $menu = (new AdminNavigationService())->profileMenu();

    expect($menu)->toHaveCount(3);
    expect(array_column($menu, 'subtitle'))
        ->toContain('Home', 'Kennwort ändern', '2-FA-Authentifizierung');
})->group('adminnav', 'profile');

it('returns empty for mediamanager_admin only', function () {
    /** @var User $mm */
    $mm = User::factory()->create();
    $mm->assignRole('mediamanager_admin');

    actingAs($mm);

    $menu = (new AdminNavigationService())->profileMenu();
    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'profile');
