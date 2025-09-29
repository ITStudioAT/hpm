<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Http\Resources\Admin\HomepageResource;
use App\Http\Resources\Admin\PageResource;
use App\Models\Page;
use App\Support\Path;
use App\Support\Structure;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validate([
            'homepage_id' => 'required|exists:homepages,id',
        ]);

        $pages = Page::where('homepage_id', $validated['homepage_id'])->orderBy('name')->get();

        return response()->json(PageResource::collection($pages), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();
        info($validated);

        // create an allowed path
        $raw = $validated['data']['path'];
        $path = Path::normalizePath($raw);

        if (Page::where('name', $request->input('data.name'))->count() > 0) abort(403, 'Diese Bezeichnung wird bereits verwendet');

        $page_structure = config('hpm.structures.page');

        $incoming = $request->input('structure', []);            // expect the JSON under "structure"

        // Normalize to match the structure (adds missing keys, removes extras)
        $normalized = Structure::normalize((array) $incoming, (array) $page_structure);

        $page = Page::create([
            'homepage_id' =>  $validated['homepage_id'],
            'name' =>  $validated['data']['name'],
            'path' =>  $path,
            'type' => 'page',
            'structure' => $normalized,
        ]);

        return response()->json(new PageResource($page), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();
        $validated['path'] = Path::normalizePath($validated['path']);


        $page->update($validated);

        return response()->json(new PageResource($page), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $page->delete();
        return response()->noContent();
    }
}
