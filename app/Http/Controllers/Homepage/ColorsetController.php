<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ColorsetController extends Controller
{
    public function css($slug)
    {
        $file = storage_path("app/private/colorsets/{$slug}.json");

        if (!file_exists($file)) {
            abort(404, "File not found: {$file}");
        }

        $data = json_decode(file_get_contents($file), true);
        if (!is_array($data)) {
            abort(500, "Invalid color set JSON.");
        }

        $css = ":root {\n";
        foreach ($data as $name => $value) {
            $css .= "  --{$name}: {$value};\n";
        }
        $css .= "}\n";

        return response($css, 200)->header('Content-Type', 'text/css');
    }


    public function list()
    {
        $dir = storage_path('app/private/colorsets');
        $files = glob($dir . '/*.json'); // alle JSON-Dateien im Ordner
        $names = [];

        foreach ($files as $file) {
            $names[] = pathinfo($file, PATHINFO_FILENAME);
        }

        return response()->json($names);
    }
}
