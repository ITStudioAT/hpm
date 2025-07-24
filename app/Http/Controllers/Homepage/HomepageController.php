<?php

namespace App\Http\Controllers\Homepage;

use Illuminate\Routing\Controller;

class HomepageController extends Controller
{
    public function index()
    {
        return view('homepage');
    }

    public function config()
    {

        $data = [
            'logo' => config('spa.logo', ''),
            'copyright' => config('hpm.copyright', ''),
            'timeout' => config('spa.timeout', 3000),
            'title' => config('hpm.title', 'Spa'),
            'company' => config('hpm.company', 'ItStudio.at'),
        ];

        return response()->json($data, 200);
    }
}
