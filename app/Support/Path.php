<?php
// app/Support/Path.php
namespace App\Support;

final class Path
{
    public static function normalizePath($raw): String
    {
        $path = trim($raw);


        // 3. replace spaces and consecutive non-allowed chars with dashes
        $path = preg_replace('/[^A-Za-z0-9\/_-]+/', '-', $path);

        // 4. collapse multiple dashes
        $path = preg_replace('/-+/', '-', $path);

        // 5. remove leading/trailing dashes or slashes
        $path = trim($path, '-/');

        return $path;
    }
}
