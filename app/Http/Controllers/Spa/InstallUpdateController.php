<?php

namespace App\Http\Controllers\Spa;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\InstallUpdateService;

class InstallUpdateController extends Controller
{
    private InstallUpdateService $installUpdateService;

    public function __construct(InstallUpdateService $installUpdateService)
    {
        $this->installUpdateService = $installUpdateService;
    }

    public function index(Request $request)
    {
        /* 1. User der Users-Tabelle laden */
        if (! $user = User::query()->first()) {
            abort(404, '1. Benutzer wurde nicht gefunden. Bitte mit php artsian user:create anlegen');
        }

        /* Rollen erzeugen */
        $roles = ['super_admin', 'admin', 'user'];
        $this->installUpdateService->createRoles($roles);

        /* 1. User super_admin zuweisen */
        $user->assignRole('super_admin');

        return response()->noContent();
    }
}
