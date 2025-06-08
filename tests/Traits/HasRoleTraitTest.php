<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Itstudioat\Hpm\Traits\HasRoleTrait;

beforeEach(function () {
    // Create a dummy controller that uses the trait
    if (! class_exists('FakeController')) {
        eval('
            class FakeController {
                use \Itstudioat\Hpm\Traits\HasRoleTrait;
            }
        ');
    }
});



it('returns true if check_spatie_role is false', function () {
    Config::set('hpm.check_spatie_role', false);

    $controller = new FakeController;

    expect($controller->userHasRole())->toBeTrue();
});

it('returns false if user is not authenticated', function () {
    Config::set('hpm.check_spatie_role', true);
    Config::set('hpm.needed_role', 'admin');

    Auth::shouldReceive('check')->once()->andReturn(false);

    $controller = new FakeController;

    expect($controller->userHasRole())->toBeFalse();
});

it('returns false if user has no required role', function () {
    $mockUser = Mockery::mock();
    $mockUser->shouldReceive('hasAnyRole')->with(['admin'])->andReturn(false);

    Config::set('hpm.check_spatie_role', true);
    Config::set('hpm.needed_role', 'admin');

    Auth::shouldReceive('check')->once()->andReturn(true);
    Auth::shouldReceive('user')->once()->andReturn($mockUser);

    $controller = new FakeController;

    expect($controller->userHasRole())->toBeFalse();
});

it('returns true if user has the required role', function () {
    $mockUser = Mockery::mock();
    $mockUser->shouldReceive('hasAnyRole')->with(['editor'])->andReturn(true);

    Config::set('hpm.check_spatie_role', true);
    Config::set('hpm.needed_role', 'editor');

    Auth::shouldReceive('check')->once()->andReturn(true);
    Auth::shouldReceive('user')->once()->andReturn($mockUser);

    $controller = new FakeController;

    expect($controller->userHasRole())->toBeTrue();
});
