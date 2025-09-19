<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Requests\Homepage\ColorSetRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
}
