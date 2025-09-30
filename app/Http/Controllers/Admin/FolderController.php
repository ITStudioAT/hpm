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
use App\Support\Path;
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

        $folder = Folder::where('homepage_id', $validated['homepage_id'])
            ->where('type', $validated['type'])
            ->first();


        $structure = config('hpm.structures.page_folders', []);

        if (!$folder) {
            $folder = Folder::create([
                'homepage_id' => $validated['homepage_id'],
                'name' => 'page_folders',
                'type' => 'page_folders',
                'structure' => $structure,

            ]);
        }

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
        $path = Path::normalizePath($validated['path'] . '/' . $validated['data']['name']);
        while (strpos($path, '//') !== false) {
            $path = str_replace('//', '/', $path);
        }
        $path = rtrim($path, '/');
        if ($path === '') $path = '/';
        if ($path[0] !== '/') $path = '/' . $path;

        // Struktur holen (bei Model-Cast ist es schon ein Array)
        $structure = $folder->structure ?? [];
        if (!isset($structure['folders']) || !is_array($structure['folders'])) {
            $structure['folders'] = [];
        }

        // Normalize to match the structure (adds missing keys, removes extras)
        $folder_structure = config('hpm.structures.page_folders');
        $incoming = $structure;            // expect the JSON under "structure"
        $structure = Structure::normalize((array) $incoming, (array) $folder_structure);

        // 🔎 Prüfen, ob der Ordner schon existiert
        if (in_array($path, $structure['folders'], true)) {
            abort(400, 'Ordner existiert bereits');
        }

        // Einfügen
        $structure['folders'][] = $path;
        $structure['folders'] = array_values(array_unique($structure['folders']));
        natcasesort($structure['folders']);   // sorts naturally, ignoring case
        $folders = array_values($structure['folders']); // reindex keys



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

        // Struktur holen (bei Model-Cast ist es schon ein Array)
        $structure = $folder->structure ?? [];
        if (!isset($structure['folders']) || !is_array($structure['folders'])) {
            $structure['folders'] = [];
        }

        // Normalize to match the structure (adds missing keys, removes extras)
        $folder_structure = config('hpm.structures.page_folders');
        $incoming = $structure;            // expect the JSON under "structure"
        $structure = Structure::normalize((array) $incoming, (array) $folder_structure);




        $folders = $structure['folders'];


        // old and new base names (e.g. "ggg" -> "ggg1")
        $new = '/' . $validated['data']['name'] ?? '';
        $path = $validated['path'];

        $new = ltrim($new, '/');

        // split into parts
        $parts = explode('/', $path);
        $parts[count($parts) - 1] = $new;

        $new = implode('/', $parts);


        // 🔎 Prüfen, ob der Ordner schon existiert
        if ($path != $new && in_array($new, $structure['folders'], true)) {
            abort(400, 'Ordner existiert bereits');
        }

        $folders = array_map(function ($folder) use ($path, $new) {
            if (strpos($folder, $path) === 0) {   // check if it starts with $old
                return $new . substr($folder, strlen($path));
            }
            return $folder;
        }, $folders);



        // tidy & save
        asort($folders);
        natcasesort($folders);   // sorts naturally, ignoring case
        $folders = array_values($folders); // reindex keys     
        $structure['folders'] = $folders;
        $folder->structure = $structure;         // if cast: ['structure' => 'array']
        $folder->save();

        return response()->json(new FolderResource($folder), 200);
    }

    /** Normalize a path without regex. Ensures leading "/" and no trailing "/" (except root). */
    private function normalize(string $p): string
    {
        $p = trim(str_replace('\\', '/', $p));
        while (strpos($p, '//') !== false) $p = str_replace('//', '/', $p);
        if ($p === '' || $p === '.') return '/';
        $p = '/' . ltrim($p, '/');
        $p = rtrim($p, '/');
        return $p === '' ? '/' : $p;
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

        // Struktur holen (bei Model-Cast ist es schon ein Array)
        $structure = $folder->structure ?? [];
        if (!isset($structure['folders']) || !is_array($structure['folders'])) {
            $structure['folders'] = [];
        }

        // Normalize to match the structure (adds missing keys, removes extras)
        $folder_structure = config('hpm.structures.page_folders');
        $incoming = $structure;            // expect the JSON under "structure"
        $structure = Structure::normalize((array) $incoming, (array) $folder_structure);

        $folders = $structure['folders'];


        $path = $validated['path'];

        $folders = array_values(array_filter($folders, function ($folder) use ($path) {
            return $folder !== $path;
        }));





        foreach ($folders as &$item) {
            if (strpos($item, $path) === 0 && (strlen($item) == strlen($path) || substr($item, strlen($path), 1) == '/')) {
                $item = substr($item, strlen($path));
            };
        }
        unset($item);

        // tidy & save
        natcasesort($folders);   // sorts naturally, ignoring case
        $folders = array_values($folders); // reindex keys
        $structure['folders'] = $folders;
        $folder->structure = $structure;         // if cast: ['structure' => 'array']

        $folder->save();

        // Aktualisieren der Page-Seiten der Homepage

        $pages = Page::where('homepage_id', $homepage_id)->where('folder', 'like', $path . '%')->get();


        foreach ($pages as $page) {

            if (strpos($page->folder, $path) === 0 && (strlen($page->folder) == strlen($path) || substr($page->folder, strlen($path), 1) == '/')) {
                $page->folder = substr($page->folder, strlen($path));
                if ($page->folder == '') $page->folder = '/';

                $page->save();
            };
        }
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
            Page::where('homepage_id', $validated['homepage_id'])->where('folder', $validated['from_folder'])->update(['folder' => $validated['to_folder']]);
        }
    }
}
