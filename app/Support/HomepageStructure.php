<?php

namespace App\Support;

use InvalidArgumentException;

class HomepageStructure
{
    public static function blueprint(string $type): array
    {
        $bp = config("hpm.structures.{$type}");
        if (!is_array($bp)) {
            throw new InvalidArgumentException("Unknown structure type: {$type}");
        }
        return $bp;
    }

    public static function make(string $type, array $overrides = []): array
    {
        return array_replace_recursive(self::blueprint($type), $overrides);
    }
}
