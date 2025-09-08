<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Services\AdminNavigationService;
use App\Services\AdminService;
use Mockery as m;

/**
 * Build an AdminController with injected mocks.
 *
 * @param  array|null  $menu Fake menu returned by AdminNavigationService::dashboardMenu()
 * @param  callable|null $adminServiceSetup Optional: configure $adminService expectations
 * @return array{0: AdminController, 1: \Mockery\MockInterface, 2: \Mockery\MockInterface}
 */
function makeAdminControllerForUnit(?array $menu = null, ?callable $adminServiceSetup = null): array
{
    // Fresh mocks each time
    $adminService = m::mock(AdminService::class);
    $navService   = m::mock(AdminNavigationService::class);

    // Default menu (can be overridden)
    $navService->shouldReceive('dashboardMenu')
        ->andReturn($menu ?? [['key' => 'dashboard']]);

    if ($adminServiceSetup) {
        $adminServiceSetup($adminService);
    }

    // Inject mocks into a real controller instance
    $controller = new AdminController($adminService, $navService);

    return [$controller, $adminService, $navService];
}
