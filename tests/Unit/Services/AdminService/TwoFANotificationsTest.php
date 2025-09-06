<?php

declare(strict_types=1);

use App\Models\User;
use App\Notifications\StandardEmail;
use App\Services\AdminService;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    config()->set('spa.token_expire_time', 10); // ensure present
});

it('sends on-demand mail for continueLoginFor2FaUser', function () {
    Notification::fake();

    $u = User::factory()->create(['email' => 'n1@test.tld']);

    (new AdminService())->continueLoginFor2FaUser($u);

    Notification::assertSentOnDemand(StandardEmail::class, function ($notification, $channels, $notifiable) use ($u) {
        return $notifiable->routeNotificationFor('mail') === $u->email;
    });
    Notification::assertSentOnDemandTimes(StandardEmail::class, 1);
});

it('sends on-demand mail for sendPasswordResetToken', function () {
    Notification::fake();

    $u = User::factory()->create(['email' => 'n2@test.tld']);

    (new AdminService())->sendPasswordResetToken(1, $u, $u->email);

    Notification::assertSentOnDemand(StandardEmail::class, function ($notification, $channels, $notifiable) use ($u) {
        return $notifiable->routeNotificationFor('mail') === $u->email;
    });
});

it('sends on-demand mail for sendRegisterToken', function () {
    Notification::fake();

    $u = User::factory()->create(['email' => 'n3@test.tld']);

    (new AdminService())->sendRegisterToken(1, $u, $u->email);

    Notification::assertSentOnDemand(StandardEmail::class, function ($notification, $channels, $notifiable) use ($u) {
        return $notifiable->routeNotificationFor('mail') === $u->email;
    });
});

it('sends on-demand mail for sendEmailValidationToken', function () {
    Notification::fake();

    $u = User::factory()->create(['email' => 'n4@test.tld']);

    (new AdminService())->sendEmailValidationToken(1, $u, $u->email);

    Notification::assertSentOnDemand(StandardEmail::class, function ($notification, $channels, $notifiable) use ($u) {
        return $notifiable->routeNotificationFor('mail') === $u->email;
    });
});
