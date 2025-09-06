<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\UserService;

uses()->group('userservice', 'sendVerificationEmail');

test('SendVerificationEmail', function () {
    // Only run if your User model provides the method (custom apps often do)
    if (! method_exists(User::class, 'sendVerificationEmail')) {
        test()->markTestSkipped('User::sendVerificationEmail() not available in this app.');
    }

    $u1 = User::factory()->create();
    $u2 = User::factory()->create();

    // Should not throw for single id nor array of ids
    (new UserService())->sendVerificationEmail($u1->id);
    (new UserService())->sendVerificationEmail([$u1->id, $u2->id]);

    expect(true)->toBeTrue(); // reached without exception
});
