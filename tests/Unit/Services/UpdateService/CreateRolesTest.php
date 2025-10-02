<?php

use App\Services\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    ensurePermissionTables();
});

it('creates missing roles through the private helper', function () {
    $service = new UpdateService();

    $method = new ReflectionMethod(UpdateService::class, 'createRoles');
    $method->setAccessible(true);

    $method->invoke($service, ['alpha', 'beta']);

    expect(Role::pluck('name')->all())->toContain('alpha', 'beta');
});
