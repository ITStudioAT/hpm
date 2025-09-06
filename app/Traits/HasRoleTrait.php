<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasRoleTrait
{
    public function userHasRole(array|string $roles): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(array_merge((array) $roles, ['super_admin']));
    }

    public function userHasAtLeastOneRole(): bool
    {
        $user = Auth::user();
        return $user?->roles()->exists() ?? false;
    }
}
