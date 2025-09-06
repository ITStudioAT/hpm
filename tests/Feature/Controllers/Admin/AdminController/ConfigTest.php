<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

uses()->group('feature', 'admincontroller', 'config');

beforeEach(function () {
    foreach (['admin','user','super_admin'] as $r) {
        Role::findOrCreate($r, 'web');
    }
    config()->set('spa.logo', 'logo.svg');
    config()->set('spa.copyright', '© 2025');
    config()->set('spa.title', 'Fresh Laravel');
    config()->set('spa.company', 'ItStudio.at');
    config()->set('spa.register_admin_allowed', true);
    config()->set('spa.timeout', 1337);
});

test('Config', function () {
    $ctl = new AdminController();

    // guest
    try {
        $res = $ctl->config(request());
    } catch (\OutOfBoundsException $e) {
        test()->markTestSkipped('Composer package "itstudioat/spa" not installed for version lookup.');
        return;
    }

    $json = $res->getData(true);
    expect($res->status())->toBe(200);
    expect($json)->toHaveKeys(['logo','title','company','version','register_admin_allowed','timeout','is_auth','user','menu']);
    expect($json['is_auth'])->toBeFalse();
    expect($json['user'])->toBeNull();

    // logged in
    $u = User::factory()->create();
    $u->assignRole('admin');
    Auth::login($u);

    $res2 = $ctl->config(request());
    $json2 = $res2->getData(true);
    expect($res2->status())->toBe(200);
    expect($json2['is_auth'])->toBeTrue();
    expect($json2['user'])->not->toBeNull();
});
