<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoadMenuRequest;
use App\Services\AdminNavigationService;

class NavigationController extends Controller
{

    public function loadMenu(LoadMenuRequest $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $action = $request->input('action');
        $navigationService = new AdminNavigationService();

        switch ($action) {

            case 'profile':
                $data = [
                    'menu' => $navigationService->profileMenu(),
                ];
                return response()->json($data, 200);
            case 'users':
                $data = [
                    'menu' => $navigationService->userMenu(),
                    'selection' => $navigationService->userSelection(),
                ];
                return response()->json($data, 200);

            case 'home':
                $data = [
                    'menu' => $navigationService->homeMenu(),
                ];
                return response()->json($data, 200);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }
}
