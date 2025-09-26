<?php

namespace App\Services;

use App\Models\Homepage;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UpdateService
{
    public function initialize($command)
    {
        if (! $user = User::query()->first()) {
            abort(404, '1. Benutzer wurde nicht gefunden. Bitte mit php artisan user:create anlegen');
        }

        $roles = ['super_admin', 'admin', 'user'];
        $this->createRoles($roles);

        $command->line('✅ Roles created, if necessary.');

        $user->assignRole('super_admin');

        $command->line('✅ First user is super_admin.');
    }

    public function updateHomepageStructures($command)
    {
        $structures = config('hpm.structures');

        foreach ($structures as $name => $schema) {
            $command->line('🔄 Updating structure of ' . $name);

            $homepages = Homepage::withoutGlobalScope('homepage_types')
                ->where('type', $name)
                ->get();

            foreach ($homepages as $homepage) {
                $current = $homepage->structure ?? [];
                if (is_string($current) && $current !== '') {
                    $decoded = json_decode($current, true);
                    $current = is_array($decoded) ? $decoded : [];
                } elseif (! is_array($current)) {
                    $current = [];
                }

                if (
                    is_array($current)
                    && array_key_exists('id', $current)
                    && ! isset($current['index'])
                    && isset($schema['index'])
                ) {
                    $current = ['index' => ['id' => $current['id']]];
                }

                $normalized = $this->normalizeStructure($current, $schema);

                $a = $current;
                $b = $normalized;
                $ksort = function (&$arr) use (&$ksort) {
                    if (! is_array($arr)) {
                        return;
                    }
                    foreach ($arr as &$value) {
                        $ksort($value);
                    }
                    ksort($arr);
                };
                $ksort($a);
                $ksort($b);

                if ($b !== $a) {
                    $homepage->structure = $normalized;
                    $homepage->save();

                    $command->line("🔧 Homepage #{$homepage->id} structure normalized.");
                } else {
                    $command->line("✅ Homepage #{$homepage->id} already up to date.");
                }
            }
        }
    }

    private function createRoles(array $roles): void
    {
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function normalizeStructure($current, $schema)
    {
        if (! is_array($schema)) {
            return is_array($current) ? $schema : ($current ?? $schema);
        }

        if (! is_array($current)) {
            return $schema;
        }

        if ($this->isAssoc($schema)) {
            $result = [];
            foreach ($schema as $key => $schemaValue) {
                $currentValue = array_key_exists($key, $current) ? $current[$key] : null;
                $result[$key] = $this->normalizeStructure($currentValue, $schemaValue);
            }

            return $result;
        }

        if (count($schema) === 0) {
            return [];
        }

        $itemSchema = $schema[0];
        $result = [];
        foreach ($current as $item) {
            $result[] = $this->normalizeStructure($item, $itemSchema);
        }

        return $result;
    }
}
