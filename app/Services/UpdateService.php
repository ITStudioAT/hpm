<?php

namespace App\Services;

use App\Models\Homepage;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UpdateService
{


    public function initialize($command)
    {

        /* 1. User der Users-Tabelle laden */
        if (! $user = User::query()->first()) {
            abort(404, '1. Benutzer wurde nicht gefunden. Bitte mit php artsian user:create anlegen');
        }

        /* Rollen erzeugen */
        $roles = ['super_admin', 'admin', 'user'];
        $this->createRoles($roles);

        $command->line('✅ Roles created, if necessary.');

        /* 1. User super_admin zuweisen */
        $user->assignRole('super_admin');

        $command->line('✅ First user is super_admin.');
    }

    public function updateHomepageStructures($command)
    {
        $structures = config('hpm.structures');

        foreach ($structures as $name => $schema) {
            $command->line('Ã°Å¸â€â€ž Updating structure of ' . $name);

            $homepages = Homepage::withoutGlobalScope('homepage_types')->where('type', $name)->get();

            foreach ($homepages as $homepage) {
                // 1) Coerce to array (handles casted array OR legacy string JSON)
                $current = $homepage->structure ?? [];
                if (is_string($current) && $current !== '') {
                    $decoded = json_decode($current, true);
                    $current = is_array($decoded) ? $decoded : [];
                } elseif (!is_array($current)) {
                    $current = [];
                }

                // 2) Legacy shim: wrap flat {"id":...} into ['index'=>['id'=>...]] if schema expects it
                if (is_array($current) && array_key_exists('id', $current) && !isset($current['index']) && isset($schema['index'])) {
                    $current = ['index' => ['id' => $current['id']]];
                }

                // 3) Normalize
                $normalized = $this->normalize_structure($current, $schema);

                // 4) Order-insensitive compare
                $a = $current;
                $b = $normalized;
                $ksort = function (&$arr) use (&$ksort) {
                    if (!is_array($arr)) return;
                    foreach ($arr as &$v) $ksort($v);
                    ksort($arr);
                };
                $ksort($a);
                $ksort($b);

                if ($b !== $a) {
                    // assign array directly; cast will handle JSON serialization
                    $homepage->structure = $normalized;
                    $homepage->save();

                    $command->line("Ã°Å¸â€Â§ Homepage #{$homepage->id} structure normalized.");
                } else {
                    $command->line("Ã¢Å“â€¦ Homepage #{$homepage->id} already up to date.");
                }
            }
        }
    }





    private function createRoles($roles)
    {
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }



    // Detect associative arrays
    private function is_assoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Normalize $current to the "shape" of $schema (deep).
     * - Unknown keys in $current are removed.
     * - Missing keys are filled from $schema.
     * - If both sides are arrays, we recurse (associative only).
     * - If types differ, $schema wins.
     */
    private function normalize_structure($current, $schema)
    {
        // If schema is not an array, return current if key exists and is scalar; else schema default
        if (!is_array($schema)) {
            // prefer existing scalar, otherwise take schema default
            return (is_array($current) ? $schema : ($current ?? $schema));
        }

        // For arrays:
        if (!is_array($current)) {
            // current isn't an array -> take the schema default array
            return $schema;
        }

        // Handle associative arrays (most config structures)
        if ($this->is_assoc($schema)) {
            $result = [];

            // Only keep keys that exist in schema (drop extras)
            foreach ($schema as $key => $schemaVal) {
                $currVal = array_key_exists($key, $current) ? $current[$key] : null;
                $result[$key] = $this->normalize_structure($currVal, $schemaVal);
            }

            return $result;
        }

        // Indexed arrays (optional strategy):
        // If schema is an indexed array, use the first element as the "item schema" (if any)
        // and normalize each item of current to that shape. If schema is empty, keep current as-is.
        if (count($schema) === 0) {
            return []; // schema says empty list
        }

        $itemSchema = $schema[0];
        $result = [];
        foreach ($current as $item) {
            $result[] = $this->normalize_structure($item, $itemSchema);
        }
        return $result;
    }
}



