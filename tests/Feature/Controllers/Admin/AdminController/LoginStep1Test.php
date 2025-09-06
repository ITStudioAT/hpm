<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\LoginStep1Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('feature', 'admincontroller', 'login');

beforeEach(function () {
    foreach (['user','admin','super_admin'] as $r) {
        Role::findOrCreate($r, 'web');
    }
});

class _LoginStep1Req extends LoginStep1Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('LoginStep1', function () {
    $u = User::factory()->create([
        'email'        => 'login1@example.test',
        'confirmed_at' => now(),
        'is_active'    => 1,
    ]);
    $u->assignRole('user');

    $ctl = new AdminController();
    $res = $ctl->loginStep1(new _LoginStep1Req([
        'email' => $u->email,
        'step'  => 'INIT',
    ]));

    expect($res->getData(true)['step'])->toBe('LOGIN_ENTER_PASSWORD');
});
