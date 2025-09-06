<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\RegisterStep3Request;
use App\Models\User;

uses()->group('feature', 'admincontroller', 'register');

beforeEach(function () {
    config()->set('spa.registered_admin_must_be_confirmed', false);
});

class _RegisterStep3Req extends RegisterStep3Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('RegisterStep3', function () {
    $user = User::factory()->create([
        'email' => 'reg3@example.test',
        'register_started_at' => now(),
        'confirmed_at' => null,
        'is_active'   => 0,
    ]);

    $ctl = new AdminController();

    $req = new _RegisterStep3Req([
        'email'         => $user->email,
        'step'          => 'INIT', // bypass token checks
        'last_name'     => 'Mustermann',
        'first_name'    => 'Max',
        'password'      => 'secret123',
        'password_repeat' => 'secret123',
    ]);

    $res = $ctl->registerStep3($req);
    $json = $res->getData(true);

    expect($res->status())->toBe(200);
    expect(in_array($json['step'], ['REGISTER_FINISHED', 'REGISTER_MUST_BE_CONFIRMED']))->toBeTrue();
});
