<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\PasswordUnknownStep4Request;
use App\Models\User;

uses()->group('feature', 'admincontroller', 'pw');

beforeEach(function () {
    config()->set('spa.token_expire_time', 10);
});

class _PwUnk4Req extends PasswordUnknownStep4Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('PasswordUnknownStep4', function () {
    $ctl = new AdminController();

    $u = User::factory()->create([
        'email'        => 'pw4@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'is_2fa'       => 0, // keep simple: only one token needed
    ]);

    $token = $u->setToken2Fa(config('spa.token_expire_time'), 1);

    $res = $ctl->passwordUnknownStep4(new _PwUnk4Req([
        'email'           => $u->email,
        'step'            => 'PASSWORD_UNKNOWN_ENTER_PASSWORD',
        'token_2fa'       => $token,
        'password'        => 'NewSecret#1',
        'password_repeat' => 'NewSecret#1',
    ]));

    expect($res->getData(true)['step'])->toBe('PASSWORD_UNKNOWN_FINISHED');
});
