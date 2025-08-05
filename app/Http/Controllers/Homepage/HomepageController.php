<?php

namespace App\Http\Controllers\Homepage;

use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Homepage\HomepageResource;
use App\Http\Requests\Homepage\LoadHomepageRequest;

class HomepageController extends Controller
{
    public function index()
    {
        return view('homepage');
    }

    public function loadHomepage(LoadHomepageRequest $request)
    {
        $preview = $request->query('preview');

        // Wenn Vorschau angefordert wird, prüfen, ob der Benutzer die Rolle 'admin' hat
        if ($preview) {
            if (! $auth_user = $this->userHasRole(['admin'])) {
                abort(403, 'Sie haben keine Berechtigung');
            }
        }

        $homepage = Homepage::findOrFail($preview);

        return response()->json(new HomepageResource($homepage), 200);
    }
}
