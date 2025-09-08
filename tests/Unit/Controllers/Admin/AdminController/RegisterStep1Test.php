<?php

/**
 * Unit — AdminController@registerStep1
 * Covers:
 *  - existing user → sendRegisterToken
 *  - new user     → createRegisterUser + sendRegisterToken

 


use App\Http\Controllers\Admin\AdminController;
use Mockery as m;

it('registerStep1 sends token for existing user', function () {
    $controller = app(AdminController::class);

    $existingUser = (object)['id' => 42, 'email' => 'r@example.test'];


    $adminServiceMock = m::mock('overload:App\Services\AdminService');

    $adminServiceMock->shouldReceive('checkRegister')
        ->once()->with(['email' => 'r@example.test'])->andReturn($existingUser);

    $adminServiceMock->shouldReceive('sendRegisterToken')
        ->once()->with(1, $existingUser, 'r@example.test');

    $request = m::mock(\App\Http\Requests\Admin\RegisterStep1Request::class);
    $request->shouldReceive('validated')->andReturn(['data' => ['email' => 'r@example.test']]);

    $response = $controller->registerStep1($request);
    $json = $response->getData(true);

    expect($response->status())->toBe(200);
    expect($json['step'])->toBe('REGISTER_ENTER_TOKEN');
});


it('registerStep1 creates user and sends token when not existing', function () {
    $controller = app(AdminController::class);

    $createdUser = (object)['id' => 77, 'email' => 'new@example.test'];


    $adminServiceMock = m::mock('overload:App\Services\AdminService');

    $adminServiceMock->shouldReceive('checkRegister')
        ->once()->with(['email' => 'new@example.test'])->andReturnNull(); // or andReturnFalse()

    $adminServiceMock->shouldReceive('createRegisterUser')
        ->once()->with(['email' => 'new@example.test'])->andReturn($createdUser);

    $adminServiceMock->shouldReceive('sendRegisterToken')
        ->once()->with(1, $createdUser, 'new@example.test');

    $request = m::mock(\App\Http\Requests\Admin\RegisterStep1Request::class);
    $request->shouldReceive('validated')->andReturn(['data' => ['email' => 'new@example.test']]);

    $response = $controller->registerStep1($request);
    $json = $response->getData(true);

    expect($response->status())->toBe(200);
    expect($json['step'])->toBe('REGISTER_ENTER_TOKEN');
});

 */
