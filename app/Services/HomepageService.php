<?php

namespace App\Services;

use App\Models\User;
use App\Models\Homepage;
use Spatie\Permission\Models\Role;

class HomepageService
{


    public function create()
    {
        $homepage = Homepage::create([
            'name' => 'Neu ' . date('Y-m-d H:i:s'),
            'path' => '/',
            'type' => 'index',
            'structure' => null,
        ]);

        return $homepage;
    }

    public function delete($id)
    {
        $homepage = Homepage::findOrFail($id);
        $homepage->delete();
    }
}
