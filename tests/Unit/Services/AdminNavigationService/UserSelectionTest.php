<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminNavigationService;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Ensure any extra roles used elsewhere exist
    Role::findOrCreate('mediamanager_admin', 'web');
});

it('returns empty selection for guests', function () {
    $menu = (new AdminNavigationService())->userSelection();

    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'userSelection');

it('returns empty selection for plain user', function () {
    actingAs($this->user);

    $menu = (new AdminNavigationService())->userSelection();

    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'userSelection');

it('returns empty selection for mediamanager_admin only', function () {
    $mm = User::factory()->create();
    $mm->assignRole('mediamanager_admin');
    actingAs($mm);

    $menu = (new AdminNavigationService())->userSelection();

    expect($menu)->toBeArray()->toBeEmpty();
})->group('adminnav', 'userSelection');

it('returns "Alle Benutzer" block for admin (structure only)', function () {
    actingAs($this->admin);

    $selection = (new AdminNavigationService())->userSelection();

    expect($selection)->toHaveCount(1);

    $block = $selection[0];

    expect($block['title'])->toBe('Alle Benutzer')
        ->and($block['icon'])->toBe('mdi-account-group')
        ->and($block['url'])->toBe('/admin/users/all_users')
        // do not assert exact infos; just ensure it exists and is an array
        ->and($block)->toHaveKey('infos')
        ->and($block['infos'])->toBeArray();
})->group('adminnav', 'userSelection');

it('returns "Alle Benutzer" block for super_admin (override)', function () {
    actingAs($this->super_admin);

    $selection = (new AdminNavigationService())->userSelection();

    expect($selection)->toHaveCount(1);

    $block = $selection[0];

    expect($block['title'])->toBe('Alle Benutzer')
        ->and($block['icon'])->toBe('mdi-account-group')
        ->and($block['url'])->toBe('/admin/users/all_users')
        ->and($block)->toHaveKey('infos')
        ->and($block['infos'])->toBeArray();
})->group('adminnav', 'userSelection');

it('still returns exactly one block for admin + mediamanager_admin', function () {
    $u = User::factory()->create();
    $u->assignRole('admin', 'mediamanager_admin');
    actingAs($u);

    $selection = (new AdminNavigationService())->userSelection();

    expect($selection)->toHaveCount(1)
        ->and($selection[0]['title'])->toBe('Alle Benutzer');
})->group('adminnav', 'userSelection');
