<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\LoginStep2Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StandardEmail;
use Spatie\Permission\Models\Role;

uses()->group('feature', 'admincontroller', 'login');

beforeEach(function () {
    foreach (['user','admin','super_admin'] as $r) {
        Role::findOrCreate($r, 'web');
    }
});

class _LoginStep2Req extends LoginStep2Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('LoginStep2', function () {
    $ctl = new AdminController();

    // A) user without 2FA -> LOGIN_SUCCESS, auth true
    $u1 = User::factory()->create([
        'email'        => 'login2a@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'password'     => Hash::make('secret'),
        'is_2fa'       => 0,
    ]);
    $u1->assignRole('user');

    $resA = $ctl->loginStep2(new _LoginStep2Req([
        'email'    => $u1->email,
        'step'     => 'LOGIN_ENTER_PASSWORD',
        'password' => 'secret',
    ]));
    $jsonA = $resA->getData(true);
    expect($jsonA['step'])->toBe('LOGIN_SUCCESS');
    expect($jsonA['auth'])->toBeTrue();
    expect($jsonA['user']['id'] ?? $jsonA['user']['data']['id'] ?? null)->not->toBeNull();

    // B) user with 2FA -> sends code & returns LOGIN_ENTER_TOKEN
    Notification::fake();
    $u2 = User::factory()->create([
        'email'        => 'login2b@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'password'     => Hash::make('secret'),
        'is_2fa'       => 1,
        'email_2fa'    => 'second@example.test',
    ]);
    $u2->assignRole('user');

    $resB = $ctl->loginStep2(new _LoginStep2Req([
        'email'    => $u2->email,
        'step'     => 'LOGIN_ENTER_PASSWORD',
        'password' => 'secret',
    ]));
    $jsonB = $resB->getData(true);
    expect($jsonB['step'])->toBe('LOGIN_ENTER_TOKEN');

    Notification::assertSentOnDemand(StandardEmail::class);
});
