<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\RegisterStep2Request;
use App\Models\User;

uses()->group('feature', 'admincontroller', 'register');

class _RegisterStep2Req extends RegisterStep2Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('RegisterStep2', function () {
    $user = User::factory()->create([
        'email' => 'reg2@example.test',
        'register_started_at' => now(),
        'email_verified_at'   => null,
    ]);

    $ctl = new AdminController();

    $req = new _RegisterStep2Req([
        'email' => $user->email,
        'step'  => 'INIT', // skip token check in service
    ]);

    $res = $ctl->registerStep2($req);
    $json = $res->getData(true);

    expect($res->status())->toBe(200);
    expect($json['step'])->toBe('REGISTER_ENTER_FIELDS');

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();
});
