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

        $menu = $request->input('action');
        $navigationService = new AdminNavigationService();

        switch ($menu) {
            case 'profile':
                $data = ['menu' => $navigationService->profileMenu()];
                break;
            case 'users':
                $data = [
                    'menu' => $navigationService->userMenu(),
                    'selection' => $navigationService->userSelection()
                ];
                break;
            case 'homepage':
                $data = ['menu' => $navigationService->homepageMenu()];
                break;
            default:

                $data = ['menu' => []];
                break;
        }


        return response()->json($data, 200);
    }
}
