<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\PasswordUnknownStep2Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses()->group('feature', 'admincontroller', 'pw');

beforeEach(function () {
    config()->set('spa.token_expire_time', 10);
    Notification::fake();
});

class _PwUnk2Req extends PasswordUnknownStep2Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('PasswordUnknownStep2', function () {
    $ctl = new AdminController();

    // case A: user without 2FA -> success
    $u1 = User::factory()->create([
        'email'        => 'pw2a@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'is_2fa'       => 0,
    ]);

    $resA = $ctl->passwordUnknownStep2(new _PwUnk2Req([
        'email' => $u1->email,
        'step'  => 'INIT',
    ]));
    expect($resA->getData(true)['step'])->toBe('PASSWORD_UNKNOWN_SUCCESS');

    // case B: user with 2FA but missing email_2fa -> abort 401
    $u2 = User::factory()->create([
        'email'        => 'pw2b@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'is_2fa'       => 1,
        'email_2fa'    => null,
    ]);

    try {
        $ctl->passwordUnknownStep2(new _PwUnk2Req([
            'email' => $u2->email,
            'step'  => 'INIT',
        ]));
        test()->fail('Expected abort 401 for missing 2FA email');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(401);
    }

    // case C: user with 2FA and email_2fa -> sends token and returns ENTER_TOKEN_2
    $u3 = User::factory()->create([
        'email'        => 'pw2c@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'is_2fa'       => 1,
        'email_2fa'    => 'second@example.test',
    ]);

    $resC = $ctl->passwordUnknownStep2(new _PwUnk2Req([
        'email' => $u3->email,
        'step'  => 'INIT',
    ]));
    expect($resC->getData(true)['step'])->toBe('PASSWORD_UNKNOWN_ENTER_TOKEN_2');
});
