<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\RegisterStep1Request;
use Illuminate\Support\Facades\Notification;

uses()->group('feature', 'admincontroller', 'register');

beforeEach(function () {
    config()->set('spa.token_expire_time', 10);
    Notification::fake();
});

class _RegisterStep1Req extends RegisterStep1Request {
    public function __construct(private array $payload = []) {}
    public function validated($key = null, $default = null) { return ['data' => $this->payload]; }
}

test('RegisterStep1', function () {
    $ctl = new AdminController();

    $req = new _RegisterStep1Req([
        'email' => 'new-admin@example.test',
        'step'  => 'INIT',
    ]);

    $res = $ctl->registerStep1($req);
    $json = $res->getData(true);
    expect($res->status())->toBe(200);
    expect($json['step'])->toBe('REGISTER_ENTER_TOKEN');
});
