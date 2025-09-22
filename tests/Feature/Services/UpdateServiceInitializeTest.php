<?php

use App\Services\UpdateService;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Support\CommandFake;

// If RefreshDatabase isn't already applied globally in tests/Pest.php, uncomment:
// uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

/**
 * Use shared helpers from tests/Support/helpers.php:
 * - ensurePermissionTables()
 * - makeUser()
 * - setHomepageSchema() (not actually needed for initialize(), but harmless)
 */

beforeEach(function () {
    ensurePermissionTables(); // makes Spatie tables available
    // No user here! Let each test control whether a user exists.
});

/**
 * It aborts(404) when there is no user in the DB.
 */
it('aborts with 404 when no user exists', function () {
    $service = new UpdateService();
    $cmd = new CommandFake();

    expect(fn() => $service->initialize($cmd))
        ->toThrow(NotFoundHttpException::class);
});

/**
 * It creates roles, assigns super_admin to the first user,
 * and writes the expected output lines.
 */
it('creates roles, assigns super_admin and outputs lines', function () {
    // Arrange: create the required user (first_name / last_name)
    $user = User::unguarded(function () {
        return User::create([
            'first_name' => 'Test',
            'last_name'  => 'User',
            'email'      => 'test@example.com',
            'password'   => bcrypt('secret'),
        ]);
    });

    $service = new UpdateService();
    $cmd = new CommandFake();

    // Act
    $service->initialize($cmd);

    // Assert roles exist
    expect(Role::where('name', 'super_admin')->exists())->toBeTrue();
    expect(Role::where('name', 'admin')->exists())->toBeTrue();
    expect(Role::where('name', 'user')->exists())->toBeTrue();

    // Assert user has super_admin
    $user->refresh();
    expect($user->hasRole('super_admin'))->toBeTrue();

    // Assert output lines
    expect($cmd->lines)->toContain('✅ Roles created, if necessary.');
    expect($cmd->lines)->toContain('✅ First user is super_admin.');
});
