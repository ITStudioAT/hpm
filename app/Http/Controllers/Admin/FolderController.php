<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyFolderRequest;
use App\Http\Requests\Admin\PageMoveRequest;
use App\Http\Requests\Admin\StoreFolderRequest;
use App\Http\Requests\Admin\UpdateFolderRequest;
use App\Http\Resources\Admin\FolderResource;
use App\Models\Folder;
use App\Models\Page;
use App\Services\FolderService;
use App\Services\PageService;
use App\Support\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $folder_types = config('hpm.folder_types');

        $validated = $request->validate([
            'homepage_id' => 'required|exists:homepages,id',
            'type'        => 'required|in:' . implode(',', $folder_types),
        ]);

        $folder = FolderService::loadOrCreateFolder($validated['homepage_id'],  $validated['type']);

        return response()->json($folder, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFolderRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();

        $folder = Folder::findOrFail($validated['folder_id']);

        // Path aufbauen und normalisieren
        $path = FolderService::createPath($validated['path'], $validated['data']['name']);

        // Struktur holen (bei Model-Cast ist es schon ein Array)
        $structure = FolderService::normalizeStructure($folder->structure ?? []);

        // 🔎 Prüfen, ob der Ordner schon existiert
        if (in_array($path, $structure['folders'], true)) {
            abort(400, 'Ordner existiert bereits');
        }

        // Einfügen
        $structure['folders'][] = $path;
        $structure['folders'] = FolderService::sortFolders($structure['folders']);

        // Save
        $folder->structure = $structure;
        $folder->save();

        return response()->json(new FolderResource($folder), 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Folder $folder) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFolderRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();

        $folder = Folder::findOrFail($validated['folder_id']);

        // Struktur holen und normalize(bei Model-Cast ist es schon ein Array)
        $structure = FolderService::normalizeStructure($folder->structure ?? []);

        $folders = $structure['folders'];
        $path = $validated['path'];

        // old and new base names (e.g. "ggg" -> "ggg1")
        $new = FolderService::createNewPath($path, $validated['data']['name']);

        // 🔎 Prüfen, ob der Ordner schon existiert
        if ($path != $new && in_array($new, $structure['folders'], true)) {
            abort(400, 'Ordner existiert bereits');
        }

        $folders = FolderService::changePathInFolders($folders, $path, $new);

        PageService::renameFolders($validated['homepage_id'], $path, $new);

        $structure['folders'] = FolderService::sortFolders($folders);
        $folder->structure = $structure;
        $folder->save();

        return response()->json(new FolderResource($folder), 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyFolderRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();
        $homepage_id = $validated['homepage_id'];

        $folder = Folder::findOrFail($validated['folder_id']);

        // Struktur holen und normalize(bei Model-Cast ist es schon ein Array)
        $structure = FolderService::normalizeStructure($folder->structure ?? []);
        $folders = $structure['folders'];
        $path = $validated['path'];

        if (FolderService::hasSubFolder($path, $folders)) abort(409, "Der Ordner beinhaltet noch andere Ordner.");

        if (Page::where('homepage_id', $homepage_id)->where('folder', $path)->count() > 0) abort(409, "Der Ordner beinhaltet noch Dateien.");


        $folders = array_values(array_filter($folders, fn($folder) => $folder !== $path));
        $structure['folders'] = FolderService::sortFolders($folders);
        $folder->structure = $structure;
        $folder->save();
    }

    public function move(PageMoveRequest $request)
    {
        if (! $auth_user = $this->userHasRole(['admin'])) {
            abort(403, 'Sie haben keine Berechtigung');
        }

        $validated = $request->validated();

        // Only move one single file
        if ($validated['move_action'] == 'active' && $validated['page_id']) {
            Page::findOrFail($validated['page_id'])->update(['folder' =>  $validated['to_folder']]);
        } else {
            PageService::renameFolders($validated['homepage_id'], $validated['from_folder'], $validated['to_folder']);
        }
    }
}
