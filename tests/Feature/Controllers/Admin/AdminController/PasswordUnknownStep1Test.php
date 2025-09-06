<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\PasswordUnknownStep1Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

uses()->group('feature', 'admincontroller', 'pw');

beforeEach(function () {
    config()->set('spa.token_expire_time', 10);
    Notification::fake();
});

class _PwUnk1Req extends PasswordUnknownStep1Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('PasswordUnknownStep1', function () {
    $user = User::factory()->create([
        'email'        => 'pw1@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'is_2fa'       => 0,
    ]);

    $ctl = new AdminController();

    $req = new _PwUnk1Req([
        'email' => $user->email,
        'step'  => 'INIT',
    ]);

    $res = $ctl->passwordUnknownStep1($req);
    $json = $res->getData(true);
    expect($res->status())->toBe(200);
    expect($json['step'])->toBe('PASSWORD_UNKNOWN_ENTER_TOKEN');
});
