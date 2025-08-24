<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteHomepageRequest;
use App\Http\Requests\Admin\LoadRecordRequest;
use App\Http\Requests\Admin\SaveHomepageRequest;
use App\Http\Requests\Admin\SaveRecordRequest;
use App\Http\Requests\Admin\ShowHomepageRequest;
use App\Http\Resources\Admin\HomepageResource;
use App\Http\Resources\Admin\RecordResource;
use App\Models\Homepage;
use App\Services\HomepageService;
use Illuminate\Http\Request;

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


    public function loadHomepage(ShowHomepageRequest $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepage = Homepage::findOrFail($request->id);
        if ($homepage->type !== 'homepage') abort(406, "Keine korrekte Anforderung einer Homepage");

        return response()->json(new HomepageResource($homepage), 200);
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
        $record->update($recordData);

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
}
