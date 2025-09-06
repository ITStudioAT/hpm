<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminService;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;

function expectAbort3(callable $cb, int $status, string $msgContains)
{
    try {
        $cb();
        test()->fail('Expected abort');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe($status);
        expect($e->getMessage())->toContain($msgContains);
    }
}

beforeEach(function () {
    foreach (['super_admin', 'admin', 'user'] as $r) {
        Role::findOrCreate($r, 'web');
    }
});

it('aborts 401 when email not found', function () {
    expectAbort3(function () {
        (new AdminService())->checkUserLogin([
            'email' => 'missing@test.tld',
            'step'  => 'INIT',
        ]);
    }, 401, 'Login funktioniert mit dieser E-Mail-Adresse nicht');
});

it('aborts 423 when not confirmed', function () {
    $u = User::factory()->create(['email' => 'l1@test.tld', 'confirmed_at' => null, 'is_active' => true]);
    expectAbort3(function () use ($u) {
        (new AdminService())->checkUserLogin(['email' => $u->email, 'step' => 'INIT']);
    }, 423, 'noch nicht bestätigt');
});

it('aborts 423 when inactive', function () {
    $u = User::factory()->create(['email' => 'l2@test.tld', 'confirmed_at' => now(), 'is_active' => false]);
    expectAbort3(function () use ($u) {
        (new AdminService())->checkUserLogin(['email' => $u->email, 'step' => 'INIT']);
    }, 423, 'gesperrt');
});

it('aborts 423 when user lacks required roles', function () {
    $u = User::factory()->create(['email' => 'l3@test.tld', 'confirmed_at' => now(), 'is_active' => true]);
    // no roles assigned
    expectAbort3(function () use ($u) {
        (new AdminService())->checkUserLogin(['email' => $u->email, 'step' => 'INIT']);
    }, 423, 'Berechtigungen');
});

it('aborts 401 for wrong password at LOGIN_ENTER_PASSWORD', function () {
    $u = User::factory()->create([
        'email'        => 'l4@test.tld',
        'confirmed_at' => now(),
        'is_active'    => true,
        'password'     => Hash::make('secret'),
    ]);
    $u->assignRole('admin');

    expectAbort3(function () use ($u) {
        (new AdminService())->checkUserLogin([
            'email'    => $u->email,
            'step'     => 'LOGIN_ENTER_PASSWORD',
            'password' => 'wrong',
        ]);
    }, 401, 'diesem Kennwort nicht');
});

it('returns user for correct password at LOGIN_ENTER_PASSWORD', function () {
    $u = User::factory()->create([
        'email'        => 'l5@test.tld',
        'confirmed_at' => now(),
        'is_active'    => true,
        'password'     => Hash::make('secret'),
    ]);
    $u->assignRole('admin');

    $res = (new AdminService())->checkUserLogin([
        'email'    => $u->email,
        'step'     => 'LOGIN_ENTER_PASSWORD',
        'password' => 'secret',
    ]);

    expect($res->id)->toBe($u->id);
});

it('aborts 401 at LOGIN_ENTER_TOKEN when password wrong', function () {
    $u = User::factory()->create([
        'email'        => 'l6@test.tld',
        'confirmed_at' => now(),
        'is_active'    => true,
        'password'     => Hash::make('secret'),
    ]);
    $u->assignRole('admin');

    expectAbort3(function () use ($u) {
        (new AdminService())->checkUserLogin([
            'email'     => $u->email,
            'step'      => 'LOGIN_ENTER_TOKEN',
            'password'  => 'WRONG',
            'token_2fa' => 'any',
        ]);
    }, 401, 'diesem Kennwort');
});

it('aborts 401 at LOGIN_ENTER_TOKEN when token invalid', function () {
    $u = User::factory()->create([
        'email'        => 'l7@test.tld',
        'confirmed_at' => now(),
        'is_active'    => true,
        'password'     => Hash::make('secret'),
    ]);
    $u->assignRole('admin');

    expectAbort3(function () use ($u) {
        (new AdminService())->checkUserLogin([
            'email'     => $u->email,
            'step'      => 'LOGIN_ENTER_TOKEN',
            'password'  => 'secret',
            'token_2fa' => 'invalid',
        ]);
    }, 401, 'Code falsch oder Zeit abgelaufen');
});
