<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use JsonException;
use Illuminate\Http\Response;

final class FonttypeService
{
    /** in‑request memo to avoid regenerating multiple times */
    private array $memo = [];

    public function serve(string $fontset): Response
    {
        $slug = $this->sanitizeSlug($fontset);
        if ($slug === null) {
            return response("/* fontset slug invalid */", 404)
                ->header('Content-Type', 'text/css; charset=UTF-8');
        }

        $path = storage_path("app/private/fontsets/{$slug}.json");
        if (!is_file($path)) {
            return response("/* fontset not found: {$slug} */", 404)
                ->header('Content-Type', 'text/css; charset=UTF-8');
        }

        $mtime        = @filemtime($path) ?: time();
        $lastModified = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
        $etag         = 'W/"fontset-' . $slug . '-' . $mtime . '"';

        if (
            request()->headers->get('If-None-Match') === $etag
            || request()->headers->get('If-Modified-Since') === $lastModified
        ) {
            return response('', 304)
                ->header('ETag', $etag)
                ->header('Last-Modified', $lastModified)
                ->header('Cache-Control', 'public, max-age=31536000, immutable')
                ->header('Vary', 'Accept-Encoding');
        }

        $css = $this->cssString($slug); // your existing method

        return response($css, 200)
            ->header('Content-Type', 'text/css; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('ETag', $etag)
            ->header('Last-Modified', $lastModified)
            ->header('Vary', 'Accept-Encoding');
    }

    public function cssString(string $fontset): string
    {
        $slug = $this->sanitizeSlug($fontset);
        if ($slug === null) {
            return "/* invalid fontset slug */";
        }

        $path = storage_path("app/private/fontsets/{$slug}.json");
        if (!is_file($path)) {
            return "";
        }

        // Cache key includes mtime so edits bust cache automatically
        $mtime   = @filemtime($path) ?: 0;
        $cacheKey = "fontset_css:{$slug}:{$mtime}";

        if (isset($this->memo[$cacheKey])) {
            return $this->memo[$cacheKey];
        }

        $css = Cache::remember($cacheKey, now()->addDay(), function () use ($path) {
            $json = @file_get_contents($path);
            if ($json === false) {
                return "/* unable to read fontset */";
            }

            try {
                $config = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                return "/* invalid fontset json */";
            }

            if (!is_array($config)) {
                return "/* invalid fontset structure */";
            }

            return $this->generateCss($config);
        });

        return $this->memo[$cacheKey] = $css;
    }

    /** Convert config → CSS */
    protected function generateCss(array $cfg): string
    {
        $out = "";

        // Base classes
        foreach ($cfg as $class => $attrs) {
            if ($class === 'breakpoints' || $class === '@font-face') continue;
            if (!is_array($attrs)) continue;

            $selector = $this->safeSelector($class);
            $out .= "{$selector} {";
            foreach ($attrs as $prop => $val) {
                $k = $this->toKebab((string) $prop);
                $out .= "{$k}: {$val};";
            }
            $out .= "}\n";
        }

        // Optional: raw @font-face blocks
        if (!empty($cfg['@font-face']) && is_array($cfg['@font-face'])) {
            foreach ($cfg['@font-face'] as $face) {
                if (!is_array($face)) continue;
                $out .= "@font-face {";
                foreach ($face as $p => $v) {
                    $out .= $this->toKebab((string) $p) . ": {$v};";
                }
                $out .= "}\n";
            }
        }

        // Breakpoints
        if (!empty($cfg['breakpoints']) && is_array($cfg['breakpoints'])) {
            foreach ($cfg['breakpoints'] as $bp => $classes) {
                $bpVal = is_numeric($bp) ? "{$bp}px" : $bp; // allow "48em" or "768"
                $out .= "@media (min-width: {$bpVal}) {\n";
                foreach ((array) $classes as $c => $attrs) {
                    if (!is_array($attrs)) continue;
                    $selector = $this->safeSelector($c);
                    $out .= "{$selector} {";
                    foreach ($attrs as $p => $v) {
                        $out .= $this->toKebab((string) $p) . ": {$v};";
                    }
                    $out .= "}\n";
                }
                $out .= "}\n";
            }
        }

        return $out;
    }

    /** only allow [a-z0-9-_ .#>:] basic selectors, prefix class with dot when plain */
    private function safeSelector(string $raw): string
    {
        $s = trim($raw);
        // if it looks like a full selector, allow limited chars; else treat as class name
        if (preg_match('~^[.#:\[\]a-z0-9_\-\s>,]+$~i', $s)) {
            return str_contains($s, '.') || str_contains($s, '#') || str_contains($s, ' ') ? $s : ".{$s}";
        }
        // fallback to class
        $clean = preg_replace('~[^a-z0-9_\-]~i', '-', $s);
        return "." . $clean;
    }

    /** camelCase → kebab-case with a tiny map for common props if desired */
    private function toKebab(string $prop): string
    {
        // special fixes
        $map = [
            'fontVariantNumeric' => 'font-variant-numeric',
        ];
        if (isset($map[$prop])) return $map[$prop];

        return strtolower(preg_replace('/([A-Z])/', '-$1', $prop));
    }

    /** prevent path traversal; allow a–z, 0–9, dash/underscore only */
    private function sanitizeSlug(string $slug): ?string
    {
        if (preg_match('~^[a-z0-9_-]+$~i', $slug)) {
            return $slug;
        }
        return null;
    }
}
