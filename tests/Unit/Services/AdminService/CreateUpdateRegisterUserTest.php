<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminService;
use Illuminate\Support\Facades\Hash;

it('createRegisterUser stores minimal fields and defaults', function () {
    $svc = new AdminService();
    $user = $svc->createRegisterUser(['email' => 'newreg@test.tld']);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->email)->toBe('newreg@test.tld')
        ->and($user->register_started_at)->not()->toBeNull()
        ->and($user->register_as)->toBe('admin')
        ->and($user->is_active)->toBeFalse();
});

it('updateRegisterUser fills names, hashes password and sets confirmation by config=false', function () {
    config()->set('spa.registered_admin_must_be_confirmed', false);

    $user = User::factory()->create([
        'email' => 'upd1@test.tld',
        'register_started_at' => now(),
        'confirmed_at' => null,
        'is_active' => 0,
    ]);

    $svc = new AdminService();
    $updated = $svc->updateRegisterUser($user, [
        'last_name'  => 'Mustermann',
        'first_name' => 'Max',
        'password'   => 'TopSecret123!',
    ]);

    expect($updated->last_name)->toBe('Mustermann')
        ->and($updated->first_name)->toBe('Max')
        ->and(Hash::check('TopSecret123!', $updated->password))->toBeTrue()
        ->and($updated->register_started_at)->toBeNull()
        ->and($updated->confirmed_at)->not()->toBeNull()
        ->and((bool)$updated->is_active)->toBeTrue();
});

it('updateRegisterUser leaves unconfirmed and inactive when config=true', function () {
    config()->set('spa.registered_admin_must_be_confirmed', true);

    $user = User::factory()->create([
        'email' => 'upd2@test.tld',
        'register_started_at' => now(),
        'confirmed_at' => null,
        'is_active' => 0,
    ]);

    $updated = (new AdminService())->updateRegisterUser($user, [
        'last_name'  => 'Muster',
        'password'   => 'PW123456',
    ]);

    expect($updated->confirmed_at)->toBeNull()
        ->and((int)$updated->is_active)->toBe(0);
});
