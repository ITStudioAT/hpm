<?php

declare(strict_types=1);

use App\Enums\TwoFaResult;
use App\Models\User;
use App\Notifications\StandardEmail;
use App\Services\UserService;
use Illuminate\Support\Facades\Notification;

uses()->group('userservice', 'check2fastep2');

beforeEach(function () {
    config()->set('spa.token_expire_time', 10);
});

test('Check2FaStep2', function () {
    $svc  = new UserService();
    $user = User::factory()->create([
        'email'                 => 'main@example.test',
        'email_2fa'             => 'old2fa@example.test',
        'email_2fa_verified_at' => null,
        'is_2fa'                => false,
    ]);

    // DELETE: clears flags
    $svc->check2FaStep2(TwoFaResult::TWO_FA_DELETE, $user, null);
    $user->refresh();
    expect((bool)$user->is_2fa)->toBeFalse()
        ->and($user->email_2fa)->toBeNull()
        ->and($user->email_2fa_verified_at)->toBeNull();

    // OK: sets is_2fa true (email already verified in step 1 scenario)
    $user->email_2fa = 'ok@example.test';
    $user->email_2fa_verified_at = now();
    $user->save();
    $svc->check2FaStep2(TwoFaResult::TWO_FA_OK, $user, 'ok@example.test');
    $user->refresh();
    expect((bool)$user->is_2fa)->toBeTrue();

    // EMAIL_MUST_BE_VERIFIED: sends code to given address
    Notification::fake();
    $svc->check2FaStep2(TwoFaResult::TWO_FA_EMAIL_MUST_BE_VERIFIED, $user, 'needverify@example.test');
    Notification::assertSentOnDemand(StandardEmail::class, function ($notification, $channels, $notifiable) {
        return $notifiable->routeNotificationFor('mail') === 'needverify@example.test';
    });

    // EMAIL_IS_NEW: also sends code
    Notification::fake();
    $svc->check2FaStep2(TwoFaResult::TWO_FA_EMAIL_IS_NEW, $user, 'new2fa@example.test');
    Notification::assertSentOnDemand(StandardEmail::class, function ($notification, $channels, $notifiable) {
        return $notifiable->routeNotificationFor('mail') === 'new2fa@example.test';
    });
});
