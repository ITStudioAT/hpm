<?php

namespace Itstudioat\Hpm\Traits;

trait HasRoleTrait
{
    public function userHasRole()
    {

        if (!config('hpm.check_spatie_role')) return true;


        $par_roles = config('hpm.needed_role');

        if (! is_array($par_roles)) {
            $roles[] = $par_roles;
        } else {
            $roles = $par_roles;
        }

        info($roles);
        info(auth()->check());
        

        if (! auth()->check()) {
            return false;
        }
        if (! $user = auth()->user()) {
            return false;
        }

        if (! $user->hasAnyRole($roles)) {
            return false;
        }

        return true;
    }
}
