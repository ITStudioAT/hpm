<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Services\FonttypeService;

class FontsetController extends Controller
{
    public function __construct(private FonttypeService $svc) {}

    public function serve(string $fontset): Response
    {
        // Reuse the already-built logic in the service (ETag, Last-Modified, etc.)
        return $this->svc->serve($fontset);
    }

    public function list()
    {
        $dir = storage_path('app/private/fontsets');

        if (!is_dir($dir)) {
            return response()->json([], 404);
        }

        // scan directory and filter JSON files
        $files = array_values(array_filter(scandir($dir), function ($file) {
            return str_ends_with($file, '.json');
        }));

        // remove .json extension
        $names = array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files);

        return response()->json($names);
    }
}
