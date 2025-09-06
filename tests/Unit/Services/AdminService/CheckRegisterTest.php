<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminService;
use Symfony\Component\HttpKernel\Exception\HttpException;

function expectAbort2(callable $cb, int $status, string $msgContains)
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
    config()->set('spa.token_expire_time', 10);
});

it('returns null when email not found', function () {
    $res = (new AdminService())->checkRegister([
        'email' => 'missing@test.tld',
        'step'  => 'INIT',
    ]);
    expect($res)->toBeNull();
});

it('aborts 401 when register not started', function () {
    $u = User::factory()->create(['email' => 'reg1@test.tld', 'register_started_at' => null]);

    expectAbort2(function () use ($u) {
        (new AdminService())->checkRegister([
            'email' => $u->email,
            'step'  => 'INIT',
        ]);
    }, 401, 'Registrieren funktioniert mit dieser E-Mail-Adresse nicht');
});

it('returns user when register started and no token step', function () {
    $u = User::factory()->create(['email' => 'reg2@test.tld', 'register_started_at' => now()]);

    $res = (new AdminService())->checkRegister([
        'email' => $u->email,
        'step'  => 'INIT',
    ]);

    expect($res->id)->toBe($u->id);
});

it('aborts 401 on REGISTER_ENTER_TOKEN when token invalid', function () {
    $u = User::factory()->create(['email' => 'reg3@test.tld', 'register_started_at' => now()]);

    expectAbort2(function () use ($u) {
        (new AdminService())->checkRegister([
            'email'     => $u->email,
            'step'      => 'REGISTER_ENTER_TOKEN',
            'token_2fa' => 'bad',
        ]);
    }, 401, 'Code falsch oder Zeit abgelaufen');
});

it('aborts 401 when last_name missing in REGISTER_ENTER_FIELDS', function () {
    // Provide a valid token so we reach the last_name validation
    $u = User::factory()->create(['email' => 'reg4@test.tld', 'register_started_at' => now()]);
    $token = $u->setToken2Fa(config('spa.token_expire_time'), 1);

    expectAbort2(function () use ($u, $token) {
        (new AdminService())->checkRegister([
            'email'     => $u->email,
            'step'      => 'REGISTER_ENTER_FIELDS',
            'token_2fa' => $token,
            'last_name' => '',
            'password'  => 'A',
            'password_repeat' => 'A',
        ]);
    }, 401, 'Nachname darf nicht leer sein');
});

it('aborts 401 when passwords mismatch in REGISTER_ENTER_FIELDS', function () {
    // Provide a valid token so we reach the password comparison
    $u = User::factory()->create(['email' => 'reg5@test.tld', 'register_started_at' => now()]);
    $token = $u->setToken2Fa(config('spa.token_expire_time'), 1);

    expectAbort2(function () use ($u, $token) {
        (new AdminService())->checkRegister([
            'email'     => $u->email,
            'step'      => 'REGISTER_ENTER_FIELDS',
            'token_2fa' => $token,
            'last_name' => 'Mustermann',
            'password'  => 'A',
            'password_repeat' => 'B',
        ]);
    }, 401, 'nicht identisch');
});
