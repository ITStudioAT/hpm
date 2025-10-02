<?php

namespace App\Services;

use App\Models\Folder;
use App\Models\Homepage;
use App\Models\User;
use App\Support\Path;
use App\Support\Structure;
use Spatie\Permission\Models\Role;

class FolderService
{

    public static function loadOrCreateFolder($homepage_id, $type): Folder
    {

        $folder = Folder::where('homepage_id', $homepage_id)
            ->where('type', $type)
            ->first();


        $structure = config('hpm.structures.page_folders', []);

        if (!$folder) {
            $folder = Folder::create([
                'homepage_id' => $homepage_id,
                'name' => 'page_folders',
                'type' =>  $type,
                'structure' => $structure,
            ]);
        }

        return $folder;
    }

    public static function createPath($path, $name): String
    {
        $path = Path::normalizePath($path . '/' . $name);
        while (strpos($path, '//') !== false) {
            $path = str_replace('//', '/', $path);
        }
        $path = rtrim($path, '/');
        if ($path === '') $path = '/';
        if ($path[0] !== '/') $path = '/' . $path;

        return $path;
    }

    public static function normalizeStructure($structure): array
    {
        if (!isset($structure['folders']) || !is_array($structure['folders'])) {
            $structure['folders'] = [];
        }

        // Normalize to match the structure (adds missing keys, removes extras)
        $folder_structure = config('hpm.structures.page_folders');
        $incoming = $structure;            // expect the JSON under "structure"
        $structure = Structure::normalize((array) $incoming, (array) $folder_structure);

        return $structure;
    }

    public static function sortFolders($folders): array
    {
        $folders = array_values(array_unique($folders));
        natcasesort($folders);   // sorts naturally, ignoring case
        $folders = array_values($folders); // reindex keys

        return  $folders;
    }

    public static function createNewPath($path, $name): String
    {
        $new = '/' . $name ?? '';

        $new = ltrim($new, '/');
        // split into parts
        $parts = explode('/', $path);
        $parts[count($parts) - 1] = $new;

        $new = implode('/', $parts);
        return $new;
    }

    public static function changePathInFolders($folders, $path, $new): array
    {
        $folders = array_map(function ($folder) use ($path, $new) {
            if (strpos($folder, $path) === 0) {   // check if it starts with $old
                return $new . substr($folder, strlen($path));
            }
            return $folder;
        }, $folders);

        return $folders;
    }

    public static function hasSubFolder($path, $folders): bool
    {
        $hasSubfolders = false;
        foreach ($folders as $folder) {
            if (strpos($folder, $path . '/') === 0) {
                $hasSubfolders = true;
                break;
            }
        }

        return $hasSubfolders;
    }
}
