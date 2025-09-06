<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AdminService;
use Symfony\Component\HttpKernel\Exception\HttpException;

function expectAbort(callable $cb, int $status, string $msgContains)
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
    // make sure token expiry exists
    config()->set('spa.token_expire_time', 10);
});

it('aborts 401 when email not found', function () {
    expectAbort(function () {
        (new AdminService())->checkPasswordUnknown([
            'email' => 'missing@example.test',
            'step'  => 'INIT',
        ]);
    }, 401, 'Kennwort zurücksetzen funktioniert mit dieser E-Mail-Adresse nicht');
});

it('aborts 423 when user not confirmed', function () {
    $u = User::factory()->create(['email' => 'u1@test.tld', 'confirmed_at' => null, 'is_active' => true]);

    expectAbort(function () use ($u) {
        (new AdminService())->checkPasswordUnknown([
            'email' => $u->email,
            'step'  => 'INIT',
        ]);
    }, 423, 'Benutzer ist noch nicht bestätigt');
});

it('aborts 423 when user inactive', function () {
    $u = User::factory()->create(['email' => 'u2@test.tld', 'confirmed_at' => now(), 'is_active' => false]);

    expectAbort(function () use ($u) {
        (new AdminService())->checkPasswordUnknown([
            'email' => $u->email,
            'step'  => 'INIT',
        ]);
    }, 423, 'Benutzer ist gesperrt');
});

it('returns user when base checks pass and no token step', function () {
    $u = User::factory()->create(['email' => 'u3@test.tld', 'confirmed_at' => now(), 'is_active' => true]);

    $res = (new AdminService())->checkPasswordUnknown([
        'email' => $u->email,
        'step'  => 'INIT',
    ]);

    expect($res->id)->toBe($u->id);
});

it('aborts 401 on token step 1 when token invalid', function () {
    $u = User::factory()->create(['email' => 'u4@test.tld', 'confirmed_at' => now(), 'is_active' => true]);

    expectAbort(function () use ($u) {
        (new AdminService())->checkPasswordUnknown([
            'email'     => $u->email,
            'step'      => 'PASSWORD_UNKNOWN_ENTER_TOKEN',
            'token_2fa' => 'invalid',
        ]);
    }, 401, 'Code falsch oder Zeit abgelaufen');
});

it('aborts 401 on token step 2 when second token invalid', function () {
    $u = User::factory()->create(['email' => 'u5@test.tld', 'confirmed_at' => now(), 'is_active' => true]);

    expectAbort(function () use ($u) {
        (new AdminService())->checkPasswordUnknown([
            'email'       => $u->email,
            'step'        => 'PASSWORD_UNKNOWN_ENTER_TOKEN_2',
            'token_2fa'   => 't1',
            'token_2fa_2' => 't2',
        ]);
    }, 401, 'Code falsch oder Zeit abgelaufen');
});

it('aborts 401 when new passwords do not match', function () {
    // Need a valid first token to reach the mismatch branch
    $u = User::factory()->create([
        'email'        => 'u6@test.tld',
        'confirmed_at' => now(),
        'is_active'    => true,
        'is_2fa'       => false, // ensure only the first token is required
    ]);

    $token = $u->setToken2Fa(config('spa.token_expire_time'), 1);

    expectAbort(function () use ($u, $token) {
        (new AdminService())->checkPasswordUnknown([
            'email'          => $u->email,
            'step'           => 'PASSWORD_UNKNOWN_ENTER_PASSWORD',
            'token_2fa'      => $token,
            'password'       => 'A',
            'password_repeat' => 'B',
        ]);
    }, 401, 'nicht identisch');
});
