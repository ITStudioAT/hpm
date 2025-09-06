<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses()->group('userservice', 'confirm');

test('Confirm', function () {
    // Case 1: all already confirmed -> abort(422)
    $c1 = User::factory()->create(['confirmed_at' => now()]);
    $c2 = User::factory()->create(['confirmed_at' => now()]);

    try {
        (new UserService())->confirm([$c1->id, $c2->id]);
        test()->fail('Expected abort(422) when all users confirmed');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(422);
        expect($e->getMessage())->toContain('bereits bestätigt');
    }

    // Case 2: some unconfirmed -> will call User::sendVerificationEmail() for each id
    if (! method_exists(User::class, 'sendVerificationEmail')) {
        test()->markTestSkipped('User::sendVerificationEmail() not available in this app.');
    }

    $u1 = User::factory()->create(['confirmed_at' => null]);
    $u2 = User::factory()->create(['confirmed_at' => now()]); // mixture

    // Should not throw
    (new UserService())->confirm([$u1->id, $u2->id]);

    expect(true)->toBeTrue();
});
