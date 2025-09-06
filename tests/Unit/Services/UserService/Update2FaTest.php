<?php

declare(strict_types=1);

use App\Enums\TwoFaResult;
use App\Models\User;
use App\Services\UserService;

uses()->group('userservice', 'update2fa');

test('Update2Fa', function () {
    $user = User::factory()->create([
        'is_2fa'                => false,
        'email_2fa'             => null,
        'email_2fa_verified_at' => null,
    ]);

    $res = (new UserService())->update2Fa($user, 'new2fa@example.test');

    $user->refresh();
    expect($res)->toBe(TwoFaResult::TWO_FA_SET);
    expect((bool)$user->is_2fa)->toBeTrue();
    expect($user->email_2fa)->toBe('new2fa@example.test');
    expect($user->email_2fa_verified_at)->not->toBeNull();
});
