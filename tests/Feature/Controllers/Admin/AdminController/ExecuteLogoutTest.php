<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses()->group('feature', 'admincontroller', 'logout');

test('ExecuteLogout', function () {
    $ctl = new AdminController();

    // not logged in -> abort 400
    try {
        $ctl->executeLogout(request());
        test()->fail('Expected abort 400 when not logged in.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(400);
    }

    // logged in -> success
    $u = User::factory()->create();
    Auth::login($u);

    $res = $ctl->executeLogout(request());
    $json = $res->getData(true);
    expect($res->status())->toBe(200);
    expect($json['message'])->toBe('Logout successful');
});
