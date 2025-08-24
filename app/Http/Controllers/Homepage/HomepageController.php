<?php

namespace App\Http\Controllers\Homepage;

use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Services\FonttypeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Homepage\HomepageResource;
use App\Http\Requests\Homepage\LoadHomepageRequest;

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


        // return response()->json(new HomepageResource($homepage));
    }
}
