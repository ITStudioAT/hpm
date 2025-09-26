<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Http\Resources\Admin\MenuResource;
use App\Http\Resources\Admin\PageResource;
use App\Models\Menu;
use App\Models\Page;
use App\Support\Structure;
use Illuminate\Http\Request;

class MenuController extends Controller
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

        $menus = Menu::where('homepage_id', $validated['homepage_id'])->orderBy('name')->get();

        return response()->json(MenuResource::collection($menus), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();

        if (Menu::where('name', $request->input('data.name'))->count() > 0) abort(403, 'Diese Bezeichnung wird bereits verwendet');

        $page_structure = config('hpm.structures.menu');

        $incoming = $request->input('structure', []);            // expect the JSON under "structure"

        // Normalize to match the structure (adds missing keys, removes extras)
        $normalized = Structure::normalize((array) $incoming, (array) $page_structure);

        $menu = Menu::create([
            'homepage_id' =>  $validated['homepage_id'],
            'name' =>  $validated['data']['name'],
            'type' => 'menu',
            'structure' => $normalized,
        ]);

        return response()->json(new MenuResource($menu), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();

        $menu->update($validated);

        return response()->json(new MenuResource($menu), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $menu->delete();
        return response()->noContent();
    }
}
