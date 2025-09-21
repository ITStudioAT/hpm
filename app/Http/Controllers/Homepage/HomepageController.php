<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\ColorSetRequest;
use App\Http\Requests\Homepage\FontRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HomepageController extends Controller
{


    public function colorSet(ColorSetRequest $request)
    {
        $colorset = $request->query('colorset');

        if (!$colorset) abort(400, "Dieses Colorset wird nicht unterstützt");
        $path = storage_path("/app/private/colorsets/{$colorset}.json");

        if (!file_exists($path)) abort(404, "Colorset '{$colorset}' wurde nicht gefunden");

        $json = file_get_contents($path);

        return response($json, 200)
            ->header('Content-Type', 'application/json');
    }

    private function font(string $font)
    {

        if (!$font) abort(400, "Diese Schriftart wird nicht unterstützt");
        $path = public_path("/storage/fonts/{$font}.css");

        if (!file_exists($path)) abort(404, "Schriftart '{$font}' wurde nicht gefunden");

        return file_get_contents($path);
    }

    public function fontset(Request $request)
    {
        $fontset = $request->query('fontset');

        if (!$fontset) abort(400, "Dieses Fontset wird nicht unterstützt");
        $path = storage_path("/app/private/fontsets/{$fontset}.json");

        if (!file_exists($path)) abort(404, "Fontset '{$fontset}' wurde nicht gefunden");

        $data = json_decode(file_get_contents($path), true);


        foreach ($data['fonts'] as $font) {
            $data[$font] = $this->font($font);
        }

        return response($data, 200);
    }

    public function loadSets(Request $request)
    {

        // Load all Colorsets
        $path = storage_path('app/private/colorsets');

        // Get all JSON files
        $files = File::files($path);

        // Extract file names without extension
        $colorset_names = collect($files)
            ->map(function ($file) {
                return pathinfo($file->getFilename(), PATHINFO_FILENAME);
            })
            ->toArray();


        // Load all Colorsets
        $path = storage_path('app/private/fontsets');

        // Get all JSON files
        $files = File::files($path);

        // Extract file names without extension
        $fontset_names = collect($files)
            ->map(function ($file) {
                return pathinfo($file->getFilename(), PATHINFO_FILENAME);
            })
            ->toArray();


        $data = [
            'colorsets' => $colorset_names,
            'fontsets' => $fontset_names,
        ];

        return response($data, 200);
    }
}
