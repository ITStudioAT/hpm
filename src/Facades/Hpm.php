<?php

namespace Itstudioat\Hpm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Itstudioat\Hpm\Hpm
 */
class Hpm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Itstudioat\Hpm\Hpm::class;
    }
}
