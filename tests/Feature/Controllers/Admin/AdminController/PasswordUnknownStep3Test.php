<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\PasswordUnknownStep3Request;
use App\Models\User;

uses()->group('feature', 'admincontroller', 'pw');

class _PwUnk3Req extends PasswordUnknownStep3Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('PasswordUnknownStep3', function () {
    $ctl = new AdminController();

    $u = User::factory()->create([
        'email'        => 'pw3@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'is_2fa'       => 1,
    ]);

    $res = $ctl->passwordUnknownStep3(new _PwUnk3Req([
        'email' => $u->email,
        'step'  => 'INIT', // skip token checks
    ]));

    expect($res->getData(true)['step'])->toBe('PASSWORD_UNKNOWN_ENTER_PASSWORD');
});
