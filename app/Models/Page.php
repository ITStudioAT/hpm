<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        // Load allowed types from config/hpm.php
        $types = Arr::wrap(config('hpm.page_types', ['page']));

        // Apply global scope: only page types
        static::addGlobalScope('page_types', function (Builder $query) use ($types) {
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
