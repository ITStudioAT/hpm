<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\LoginStep3Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses()->group('feature', 'admincontroller', 'login');

beforeEach(function () {
    config()->set('spa.token_expire_time', 10);
    foreach (['user','admin','super_admin'] as $r) {
        Role::findOrCreate($r, 'web');
    }
});

class _LoginStep3Req extends LoginStep3Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('LoginStep3', function () {
    $u = User::factory()->create([
        'email'        => 'login3@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
        'password'     => Hash::make('secret'),
        'is_2fa'       => 1,
    ]);
    $u->assignRole('user');

    $token = $u->setToken2Fa(config('spa.token_expire_time'), 1);

    $ctl = new AdminController();
    $res = $ctl->loginStep3(new _LoginStep3Req([
        'email'     => $u->email,
        'step'      => 'LOGIN_ENTER_TOKEN',
        'password'  => 'secret',
        'token_2fa' => $token,
    ]));

    $json = $res->getData(true);
    expect($json['step'])->toBe('LOGIN_SUCCESS');
    expect($json['auth'])->toBeTrue();
});
