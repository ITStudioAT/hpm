<?php

namespace Itstudioat\Hpm\Http\Controllers\Admin;

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
        $filename = resource_path(config('hpm.pv_homepage_path') . $source . ".vue");


        $vuedataService = new VuedataService();
        $stream = $vuedataService->read($filename);

        $data =
            [
                'filename' => $filename,
                'file_exists' => file_exists($filename),
                'streamx' => $stream,
            ];

        return response()->json($data, 200);




        if ($stream['success'] == false) abort(500,  $stream['error']);

        $data = ['hpm' => $stream['data']['hpm']];
        return response()->json($data, 200);
    }


    public function saveHpm(Request $request)
    {
        if (!$this->userHasRole()) abort(403, 'Not allowed');
        $source = $request['source'];
        $data = $request['data'];

        $filename = resource_path(config('hpm.pv_homepage_path') . $source . ".vue");

        $vuedataService = new VuedataService();
        $stream = $vuedataService->write($filename, ['hpm' => $data]);
        if ($stream['status'] != 'success') abort(500,  $stream['error']);

        $data = ['hpm' => $data];
        return response()->json($data, 200);
    }
}
