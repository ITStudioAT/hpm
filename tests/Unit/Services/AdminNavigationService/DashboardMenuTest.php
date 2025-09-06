<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminNavigationService;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    Role::findOrCreate('mediamanager_admin', 'web');
});

/** Helper */
function makeNamedUser(array $roles = [], string $last = 'Mustermann', string $first = 'Max'): User
{
    /** @var \App\Models\User $u */
    $u = User::factory()->create([
        'last_name'  => $last,
        'first_name' => $first,
        'password'   => bcrypt('secret'),
    ]);

    if ($roles) {
        $u->assignRole($roles);
    }

    return $u;
}

it('returns an empty dashboard menu for guests', function () {
    $service = new AdminNavigationService();
    expect($service->dashboardMenu())->toBeArray()->toBeEmpty();
})->group('adminnav', 'dashboard');

it('shows admin items for admin and hides mediamanager without role', function () {
    $user = makeNamedUser(['admin']);
    actingAs($user);

    $menu  = (new AdminNavigationService())->dashboardMenu();
    $links = array_column($menu, 'to');

    expect($links)->toContain('/admin', '/admin/dashboard', '/admin/homepage', '/admin/users', '/admin/profile')
        ->not->toContain('/admin/mm');

    $clicks = array_column($menu, 'click');
    expect($clicks)->toContain('logout');
})->group('adminnav', 'dashboard');

it('shows mediamanager item only for mediamanager_admin role', function () {
    $user = makeNamedUser(['mediamanager_admin']);
    actingAs($user);

    $links = array_column((new AdminNavigationService())->dashboardMenu(), 'to');

    expect($links)->toContain('/admin', '/admin/mm', '/admin/profile')
        ->not->toContain('/admin/dashboard', '/admin/homepage', '/admin/users');
})->group('adminnav', 'dashboard');

it('shows minimal menu for plain user', function () {
    $user = makeNamedUser(['user']);
    actingAs($user);

    $links = array_column((new AdminNavigationService())->dashboardMenu(), 'to');

    expect($links)->toContain('/admin', '/admin/profile')
        ->not->toContain('/admin/dashboard', '/admin/homepage', '/admin/users', '/admin/mm');

    $clicks = array_column((new AdminNavigationService())->dashboardMenu(), 'click');
    expect($clicks)->toContain('logout');
})->group('adminnav', 'dashboard');

/** 🔁 UPDATED: super_admin gets everything */
it('super_admin gets ALL items (admin + mediamanager + profile + logout)', function () {
    $user = makeNamedUser(['super_admin']);
    actingAs($user);

    $menu  = (new AdminNavigationService())->dashboardMenu();
    $links = array_column($menu, 'to');

    expect($links)->toContain(
        '/admin',            // Home
        '/admin/dashboard',  // admin
        '/admin/homepage',   // admin
        '/admin/users',      // admin
        '/admin/mm',         // mediamanager_admin gated, but super_admin overrides
        '/admin/profile'     // profile
    );

    $clicks = array_column($menu, 'click');
    expect($clicks)->toContain('logout');
})->group('adminnav', 'dashboard');

it('sets the profile menu title to the trimmed "last first" (max 17 chars)', function () {
    $user = makeNamedUser(['admin'], 'Sehrsehrlang', 'NameMitZusatz');
    actingAs($user);

    $menu    = (new AdminNavigationService())->dashboardMenu();
    $profile = collect($menu)->firstWhere('to', '/admin/profile');
    $expected = substr('Sehrsehrlang NameMitZusatz', 0, 17);

    expect($profile)->not->toBeNull()
        ->and($profile['title'])->toBe($expected)
        ->and(strlen($profile['title']))->toBeLessThanOrEqual(17);
})->group('adminnav', 'dashboard');
