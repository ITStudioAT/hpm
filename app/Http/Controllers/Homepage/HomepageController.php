<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homepage\LoadHomepageRequest;
use App\Http\Requests\Homepage\LoadRecordRequest;
use App\Http\Resources\Homepage\HomepageResource;
use App\Http\Resources\Homepage\RecordResource;
use App\Models\Homepage;
use App\Services\FonttypeService;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        return view('homepage');
    }

    public function loadHomepage(LoadHomepageRequest $request, FonttypeService $fonts)
    {
        $preview = $request->validated()['preview'] ?? null;
        if ($preview !== null && !$this->userHasRole(['admin'])) abort(403);

        if ($preview) {
            $homepage = Homepage::findOrFail($preview);
        } else {
            $homepage = Homepage::where('type', 'homepage')->first();
        }


        if (!$homepage) abort(406, "Es konnte keine Homepage gefunden werden.");

        $fontset = data_get($homepage->structure, 'fonts.fontType', 'default');

        // compute mtime for version
        $path = storage_path("app/private/fontsets/{$fontset}.json");
        $version = is_file($path) ? filemtime($path) : time();

        // Either return just the version...

        return response()->json([
            'data' => new HomepageResource($homepage),
            'meta' => [
                'fontset' => $fontset,
                'fontVersion' => $version,
            ],
        ]);
    }

    public function loadRecord(LoadRecordRequest $request)
    {


        $homepage = Homepage::findOrFail($request->homepage_id);
        if ($homepage->type !== 'homepage') abort(406, "Keine korrekte Anforderung einer Homepage");

        $record = Homepage::findOrFail($request->record_id);
        if ($record->homepage_id != $homepage->id) abort(406, "Keine korrekte Anforderung einer Seite einer Homepage");

        return response()->json(new RecordResource($record), 200);
    }
}
