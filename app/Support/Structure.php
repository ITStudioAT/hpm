<?php
// app/Support/Structure.php
namespace App\Support;

final class Structure
{
    /**
     * Normalize $input so it matches $structure:
     * - keep only keys present in $structure
     * - add missing keys with default values from $structure
     * - recursively normalize nested arrays
     * - for list arrays, use a single-element prototype (if provided)
     * - cast scalars to the default's type when possible
     */
    public static function normalize(array $input, array $structure): array
    {
        $result = [];

        foreach ($structure as $key => $spec) {
            if (array_key_exists($key, $input)) {
                $val = $input[$key];

                if (is_array($spec)) {
                    $result[$key] = self::normalizeArray($val, $spec);
                } else {
                    $result[$key] = self::castToSpecType($val, $spec);
                }
            } else {
                // missing key → use default from structure
                $result[$key] = is_array($spec)
                    ? self::normalize([], $spec)
                    : $spec;
            }
        }

        // unknown keys in $input are intentionally dropped
        return $result;
    }

    private static function normalizeArray($val, array $spec): array
    {
        if (!is_array($val)) {
            // wrong type in input → fallback to structure defaults
            return self::normalize([], $spec);
        }

        // If $spec is a "list prototype" (single numeric element), normalize each item with it
        if (self::isList($spec) && count($spec) === 1) {
            $proto = $spec[0];
            $out = [];
            foreach ($val as $item) {
                $out[] = is_array($proto)
                    ? self::normalize(is_array($item) ? $item : [], $proto)
                    : self::castToSpecType($item, $proto);
            }
            return $out;
        }

        // Associative array → recurse by keys
        return self::normalize($val, $spec);
    }

    private static function isList(array $arr): bool
    {
        return array_keys($arr) === range(0, count($arr) - 1);
    }

    private static function castToSpecType($value, $spec)
    {
        if ($spec === null) {
            // no type hint from spec → keep as-is
            return $value;
        }

        $type = gettype($spec);

        try {
            switch ($type) {
                case 'integer':
                    return (int) $value;
                case 'double':
                    return (float) $value;
                case 'boolean':
                    // accept "true"/"false"/"1"/"0"
                    if (is_string($value)) {
                        $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                        return $filtered ?? (bool) $value;
                    }
                    return (bool) $value;
                case 'string':
                    return (string) $value;
                default:
                    return $value; // arrays handled elsewhere; objects left as-is
            }
        } catch (\Throwable $e) {
            return $spec; // on casting failure, fall back to default
        }
    }
}
