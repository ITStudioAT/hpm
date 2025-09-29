<?php
// app/Support/Path.php
namespace App\Support;

final class Path
{
    public static function normalizePath($raw): String
    {
        $path = trim($raw);

        // 2. convert to lowercase (optional, if you want consistency)
        $path = strtolower($path);

        // 3. replace spaces and consecutive non-allowed chars with dashes
        $path = preg_replace('/[^a-z0-9\/_-]+/', '-', $path);

        // 4. collapse multiple dashes
        $path = preg_replace('/-+/', '-', $path);

        // 5. remove leading/trailing dashes or slashes
        $path = trim($path, '-/');

        return $path;
    }
}
