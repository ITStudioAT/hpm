<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\DB;



class PageService
{

    public static function renameFolders($homepage_id, $from, $to)
    {
        Page::where('homepage_id', $homepage_id)
            ->where(function ($q) use ($from) {
                $q->where('folder', $from)
                    ->orWhere('folder', 'like', $from . '/%');
            })
            ->update([
                'folder' => DB::raw("CONCAT('$to', SUBSTRING(folder, " . (strlen($from) + 1) . "))")
            ]);
    }
}
