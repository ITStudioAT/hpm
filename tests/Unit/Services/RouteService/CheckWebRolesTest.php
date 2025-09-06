<?php

declare(strict_types=1);

use App\Enums\RouteResult;
use App\Models\User;
use App\Services\RouteService;
use Spatie\Permission\Models\Role;

uses()->group('routeservice', 'webroles');

// track files we create so we can delete them
$__createdMetaFiles = [];

beforeAll(function () use (&$__createdMetaFiles) {
    // compute inside lifecycle (app is booted now)
    $metaDir = base_path('routes/meta/web');
    if (! is_dir($metaDir)) {
        @mkdir($metaDir, 0777, true);
    }

    // helper to write a meta file
    $writeMeta = function (string $group, array $roles) use (&$__createdMetaFiles, $metaDir) {
        $file = $metaDir . DIRECTORY_SEPARATOR . $group . '.php';
        $content = "<?php\n\nreturn " . var_export(['roles' => $roles], true) . ";\n";
        file_put_contents($file, $content);
        $__createdMetaFiles[] = $file;
    };

    // Create isolated meta groups just for these tests
    $writeMeta('rs-web-a', [
        '/rs-web-a'      => ['admin'],   // exact
        '/rs-web-a/open' => [],          // public
        '/rs-web-a/*'    => ['editor'],  // wildcard also matches '/rs-web-a'
    ]);

    $writeMeta('rs-web-b', [
        '/rs-web-b/*'    => ['editor'],
    ]);
});

afterAll(function () use (&$__createdMetaFiles) {
    foreach ($__createdMetaFiles as $f) {
        @unlink($f);
    }
});

beforeEach(function () {
    foreach (['admin', 'editor', 'user', 'super_admin'] as $r) {
        Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
    }
});

test('CheckWebRoles', function () {
    $svc = new RouteService();

    // 1) Group without meta file -> NOT_FOUND
    $notFound = $svc->checkWebRoles(null, '/rs-web-missing/anything');
    expect($notFound)->toBe(RouteResult::NOT_FOUND);

    // 2) Public route -> ALLOWED (guest)
    $pub = $svc->checkWebRoles(null, '/rs-web-a/open');
    expect($pub)->toBe(RouteResult::ALLOWED);

    // 3) Protected route (exact) -> NOT_EXISTS when guest
    $guestProtected = $svc->checkWebRoles(null, '/rs-web-a');
    expect($guestProtected)->toBe(RouteResult::NOT_EXISTS);

    // 4) Protected route -> NOT_ALLOWED when logged-in but lacking role
    $uUser = User::factory()->create();
    $uUser->assignRole('user');
    $notAllowed = $svc->checkWebRoles($uUser, '/rs-web-a');
    expect($notAllowed)->toBe(RouteResult::NOT_ALLOWED);

    // 5) Allowed when user has required role (admin)
    $uAdmin = User::factory()->create();
    $uAdmin->assignRole('admin');
    $allowedAdmin = $svc->checkWebRoles($uAdmin, '/rs-web-a');
    expect($allowedAdmin)->toBe(RouteResult::ALLOWED);

    // 6) Wildcard covers root and nested (editor role)
    $uEditor = User::factory()->create();
    $uEditor->assignRole('editor');
    $okRoot   = $svc->checkWebRoles($uEditor, '/rs-web-b');
    $okNested = $svc->checkWebRoles($uEditor, '/rs-web-b/anything/here');
    expect($okRoot)->toBe(RouteResult::ALLOWED);
    expect($okNested)->toBe(RouteResult::ALLOWED);

    // 7) super_admin overrides anything required
    $uSA = User::factory()->create();
    $uSA->assignRole('super_admin');
    $saOk = $svc->checkWebRoles($uSA, '/rs-web-a/whatever');
    expect($saOk)->toBe(RouteResult::ALLOWED);
});
