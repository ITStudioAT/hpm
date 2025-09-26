<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHomepageRequest;
use App\Http\Requests\Admin\UpdateHomepageRequest;
use App\Http\Resources\Admin\HomepageResource;
use App\Models\Homepage;
use App\Support\Structure;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepages = Homepage::where('type', 'homepage')->orderBy('name')->get();

        return response()->json(HomepageResource::collection($homepages), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHomepageRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        if (Homepage::where('name', $request->input('name'))->count() > 0) abort(403, 'Diese Bezeichnung wird bereits verwendet');

        $homepage_structure = config('hpm.structures.homepage');

        $incoming = $request->input('structure', []);            // expect the JSON under "structure"

        // Normalize to match the structure (adds missing keys, removes extras)
        $normalized = Structure::normalize((array) $incoming, (array) $homepage_structure);


        $homepage = Homepage::create([
            'name' => $request->input('name'),
            'type' => 'homepage',
            'structure' => $normalized,
        ]);

        return response()->json(new HomepageResource($homepage), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Homepage $homepage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomepageRequest $request, Homepage $homepage)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepage->update($request->validated());
        return response()->json(new HomepageResource($homepage), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homepage $homepage)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $homepage->delete();
        return response()->noContent();
    }
}
