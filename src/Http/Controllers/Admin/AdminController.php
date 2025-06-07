<?php

namespace Itstudioat\Hpm\src\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Itstudioat\Hpm\Traits\HasRoleTrait;
use Itstudioat\Vuedata\Services\VuedataService;


class AdminController extends Controller
{

    use HasRoleTrait;

    public function getConfig()
    {
        if (!$this->userHasRole()) abort(403, 'Not allowed');
        $data = [
            'version' => config('hpm.version'),
        ];
        return response()->json($data, 200);
    }



    public function getHpm(Request $request)
    {
        if (!$this->userHasRole()) abort(403, 'Not allowed');
        $source = $request->query('source');
        $filename = resource_path('vendor/hpm/js/pages/pv_homepage/' . $source . ".vue");

        $vuedataService = new VuedataService();
        $stream = $vuedataService->read($filename);

        $data = ['hpm' => $stream['hpm']];
        return response()->json($data, 200);
    }


    public function saveHpm(Request $request)
    {
        if (!$this->userHasRole()) abort(403, 'Not allowed');
        $source = $request['source'];
        $data = $request['data'];

        $filename = resource_path('vendor/hpm/js/pages/pv_homepage/' . $source . ".vue");

        $vuedataService = new VuedataService();
        $vuedataService->write($filename, ['hpm' => $data]);

        $data = ['hpm' => $data];
        return response()->json($data, 200);
    }
}
