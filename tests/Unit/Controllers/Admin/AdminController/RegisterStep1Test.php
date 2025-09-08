<?php

declare(strict_types=1);

use App\Models\User;
use Mockery as m;

require_once __DIR__ . '/_helpers.php';

uses()->group('unit', 'admincontroller', 'register');

afterEach(fn() => m::close());

test('registerStep1 sends token for existing user', function () {
    $existing = new User();                 // real instance, not persisted
    $existing->id    = 42;
    $existing->email = 'r@example.test';

    [$ctl, $adminService] = makeAdminControllerForUnit(
        menu: [['key' => 'dashboard']],
        adminServiceSetup: function ($svc) use ($existing) {
            $svc->shouldReceive('checkRegister')
                ->once()
                ->with(['email' => 'r@example.test'])
                ->andReturn($existing);

            $svc->shouldReceive('sendRegisterToken')
                ->once()
                ->with(1, $existing, 'r@example.test');
        }
    );

    $request = m::mock(\App\Http\Requests\Admin\RegisterStep1Request::class);
    $request->shouldReceive('validated')->andReturn(['data' => ['email' => 'r@example.test']]);

    $response = $ctl->registerStep1($request);
    $json = $response->getData(true);

    expect($response->status())->toBe(200);
    expect($json['step'])->toBe('REGISTER_ENTER_TOKEN');
});

test('registerStep1 creates user and sends token when not existing', function () {
    $created = new User();
    $created->id    = 77;
    $created->email = 'new@example.test';

    [$ctl, $adminService] = makeAdminControllerForUnit(
        adminServiceSetup: function ($svc) use ($created) {
            $svc->shouldReceive('checkRegister')
                ->once()
                ->with(['email' => 'new@example.test'])
                ->andReturnNull();

            $svc->shouldReceive('createRegisterUser')
                ->once()
                ->with(['email' => 'new@example.test'])
                ->andReturn($created);

            $svc->shouldReceive('sendRegisterToken')
                ->once()
                ->with(1, $created, 'new@example.test');
        }
    );

    $request = m::mock(\App\Http\Requests\Admin\RegisterStep1Request::class);
    $request->shouldReceive('validated')->andReturn(['data' => ['email' => 'new@example.test']]);

    $response = $ctl->registerStep1($request);
    $json = $response->getData(true);

    expect($response->status())->toBe(200);
    expect($json['step'])->toBe('REGISTER_ENTER_TOKEN');
});
