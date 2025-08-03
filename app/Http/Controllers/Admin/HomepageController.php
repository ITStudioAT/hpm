<?php

namespace App\Http\Controllers\Admin;

use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteHomepageRequest;
use App\Http\Resources\Admin\HomepageResource;
use App\Http\Requests\Admin\ShowHomepageRequest;
use App\Services\HomepageService;

class HomepageController extends Controller
{

    public function loadHomepages(Request $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }


        $homepages = Homepage::where('type', 'index')
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

        return response()->json(new HomepageResource($homepage), 200);
    }


    public function saveHomepage(Request $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepageData = $request->homepage;

        $homepage = Homepage::findOrFail($homepageData['id']);
        $homepage->update($homepageData);

        return response()->json(new HomepageResource($homepage), 200);
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
