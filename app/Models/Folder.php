<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class Folder extends Model
{
    protected $guarded = [];
    protected $casts = ['structure' => 'array'];
    protected $table = 'homepages';

    protected static function booted(): void
    {
        // Load allowed types from config/hpm.php
        $types = Arr::wrap(config('hpm.folder_types', ['page_folder']));

        // Apply global scope: only page types
        static::addGlobalScope('folder_types', function (Builder $query) use ($types) {
            $query->whereIn('type', $types);
        });

        // Set default type on creation
        static::creating(function ($model) use ($types) {
            if (blank($model->type)) {
                $model->type = $types[0];
            }
        });
    }
}
