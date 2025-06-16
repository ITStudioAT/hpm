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



    public function getJson(Request $request)
    {

        if (!$this->userHasRole()) abort(403, 'Not allowed');
        $source = $request->query('source');

        $path = resource_path(config('hpm.json_path')  . '/' . $source . ".json");

        if (!file_exists($path)) {
            abort(404, 'Datei nicht gefunden.');
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        $id = 0;
        foreach ($data['items'] as &$item) {
            $item = ['id' => $id] + $item; // 'id' will now be the first key
            $id++;
        }




        return response()->json($data, 200);
    }

    public function saveJson(Request $request)
    {
        // Zugriffsschutz
        if (!$this->userHasRole()) {
            abort(403, 'Not allowed');
        }

        // Request-Daten
        $source = $request['source'];
        $data = $request['data'];

        // Pfad zur Datei
        $path = resource_path(config('hpm.json_path') . '/' . $source . ".json");

        // Falls Datei nicht existiert → Fehler oder neu anlegen (optional)
        if (!file_exists($path)) {
            abort(404, 'Datei nicht gefunden.');
            // Alternativ zum Anlegen:
            // file_put_contents($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // Schreibe die neuen Daten in die Datei
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Datei erneut lesen (zur Bestätigung oder Rückgabe)
        $json = file_get_contents($path);
        $savedData = json_decode($json, true);

        // Rückgabe der gespeicherten Daten
        return response()->json($savedData, 200);
    }



    public function getHpm(Request $request)
    {

        if (!$this->userHasRole()) abort(403, 'Not allowed');
        $source = $request->query('source');
        $filename = resource_path(config('hpm.pv_homepage_path') . $source . ".vue");


        $vuedataService = new VuedataService();
        $stream = $vuedataService->read($filename);

        /*
        $data =
            [
                'filename' => $filename,
                'file_exists' => file_exists($filename),
                'stream' => $stream,
            ];

        return response()->json($data, 200);
*/



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
