<?php

declare(strict_types=1);

use App\Enums\TwoFaResult;
use App\Models\User;
use App\Services\UserService;

uses()->group('userservice', 'check2fa');

test('Check2Fa', function () {
    $svc = new UserService();

    $user = User::factory()->create([
        'email'                 => 'main@example.test',
        'email_2fa'             => '2fa@example.test',
        'email_2fa_verified_at' => null,
        'is_2fa'                => false,
    ]);

    // If is_2fa not wanted
    expect($svc->check2Fa($user, false, ''))->toBe(TwoFaResult::TWO_FA_DELETE);

    // 2FA email equal to primary
    expect($svc->check2Fa($user, true, 'main@example.test'))
        ->toBe(TwoFaResult::TWO_FA_EMAIL_AND_2FA_EMAIL_MUST_NOT_BE_EQUAL);

    // same as stored, verified => OK
    $user->email_2fa_verified_at = now();
    $user->save();
    expect($svc->check2Fa($user, true, '2fa@example.test'))->toBe(TwoFaResult::TWO_FA_OK);

    // same as stored, but not verified => MUST_BE_VERIFIED
    $user->email_2fa_verified_at = null;
    $user->save();
    expect($svc->check2Fa($user, true, '2fa@example.test'))->toBe(TwoFaResult::TWO_FA_EMAIL_MUST_BE_VERIFIED);

    // missing email_2fa
    expect($svc->check2Fa($user, true, ''))->toBe(TwoFaResult::TWO_FA_ERROR);

    // brand new email -> EMAIL_IS_NEW
    expect($svc->check2Fa($user, true, 'new2fa@example.test'))->toBe(TwoFaResult::TWO_FA_EMAIL_IS_NEW);
});
