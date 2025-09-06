<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CopyRecordRequest;
use App\Http\Requests\Admin\DeleteHomepageRequest;
use App\Http\Requests\Admin\DeleteRecordRequest;
use App\Http\Requests\Admin\LoadHeadersRequest;
use App\Http\Requests\Admin\LoadRecordRequest;
use App\Http\Requests\Admin\SaveHomepageRequest;
use App\Http\Requests\Admin\SaveRecordRequest;
use App\Http\Requests\Admin\ShowHomepageRequest;
use App\Http\Resources\Admin\HeaderResource;
use App\Http\Resources\Admin\HomepageResource;
use App\Http\Resources\Admin\RecordResource;
use App\Models\Homepage;
use App\Services\HomepageService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HomepageController extends Controller
{

    public function loadHomepages(Request $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepages = Homepage::where('type', 'homepage')
            ->orderBy('name', 'desc')
            ->get();

        return response()->json(HomepageResource::collection($homepages), 200);
    }

    public function loadHeaders(LoadHeadersRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        if (!$id = $request->id) abort(406, "Es existiert keine passende Homepage");

        $headers = Homepage::where('homepage_id', $id)->where('type', 'header')
            ->orderBy('name', 'desc')
            ->get();

        return response()->json(HeaderResource::collection($headers), 200);
    }


    public function loadHomepage(ShowHomepageRequest $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $id = $request->id;
        if ($id) {
            $homepage = Homepage::findOrFail($request->id);
            if ($homepage->type !== 'homepage') abort(406, "Keine korrekte Anforderung einer Homepage");
        } else {
            $homepage = Homepage::where('type', 'homepage')->first();
            if (!$homepage) abort(406, "Es existiert keine Homepage");
        }



        $fontset = data_get($homepage->structure, 'fonts.fontType', 'default');

        // compute mtime for version
        $path = storage_path("app/private/fontsets/{$fontset}.json");
        $version = is_file($path) ? filemtime($path) : time();

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

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepage = Homepage::findOrFail($request->homepage_id);
        if ($homepage->type !== 'homepage') abort(406, "Keine korrekte Anforderung einer Homepage");

        $record = Homepage::findOrFail($request->record_id);
        if ($record->homepage_id != $homepage->id) abort(406, "Keine korrekte Anforderung einer Seite einer Homepage");

        return response()->json(new RecordResource($record), 200);
    }


    public function saveHomepage(SaveHomepageRequest $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepageData = $request->homepage;

        $homepage = Homepage::findOrFail($homepageData['id']);
        $homepage->update($homepageData);

        return response()->json(new HomepageResource($homepage), 200);
    }


    public function saveRecord(SaveRecordRequest $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $recordData = $request->record;


        $record = Homepage::findOrFail($recordData['id']);

        // Fill everything except 'structure' first
        $record->fill(Arr::except($recordData, ['structure']));

        // Only update structure if key is present in payload (even if null or [])
        if (Arr::exists($recordData, 'structure')) {
            $record->structure = $recordData['structure']; // could be array, null, etc.
        }
        // If 'structure' key is NOT present, we do nothing -> keeps existing structure
        $record->save();


        return response()->json(new RecordResource($record), 200);
    }



    public function copyRecord(CopyRecordRequest $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $id = $request->id;

        $record = Homepage::findOrFail($id);
        $newRecord = $record->replicate();
        $newRecord->name = $newRecord->name . ' (Kopie)';
        $newRecord->save();


        return response()->json(new RecordResource($record), 200);
    }

    public function createHomepage(Request $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepageService = new HomepageService();
        $homepage = $homepageService->create();

        return response()->json(new HomepageResource($homepage), 200);
    }

    public function deleteHomepage(DeleteHomepageRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }
        $homepageService = new HomepageService();
        $homepage = $homepageService->delete($request->id);

        return response()->noContent();
    }

    public function deleteRecord(DeleteRecordRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepageService = new HomepageService();
        $homepage = $homepageService->deleteRecord($request->id);

        return response()->noContent();
    }
}
