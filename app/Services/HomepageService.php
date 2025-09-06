<?php

namespace App\Services;

use App\Models\Homepage;
use App\Support\HomepageStructure;

class HomepageService
{
    public function create()
    {
        // HOMEPAGE
        $homepage = $this->createHomepageRecord(
            'Neu ' . now()->format('Y-m-d H:i:s'),
            null,
            '',
            'homepage',
            HomepageStructure::make('homepage')
        );

        // INDEX
        $index = $this->createHomepageRecord(
            'Startseite',
            $homepage->id,
            '/',
            'index',
            HomepageStructure::make('index')
        );

        $homepage->update(['structure->index->id' => $index->id]);

        // HEADER
        $header = $this->createHomepageRecord(
            'Kopfzeile (Standard)',
            $homepage->id,
            '',
            'header',
            HomepageStructure::make('header')
        );
        $index->update(['structure->header->id' => $header->id]);

        // FOOTER
        $footer = $this->createHomepageRecord(
            'Fußzeile (Standard)',
            $homepage->id,
            '',
            'footer',
            HomepageStructure::make('footer')
        );
        $index->update(['structure->footer->id' => $footer->id]);

        return $homepage;
    }

    private function createHomepageRecord($name, $id, $path, $type, $structure)
    {
        return Homepage::create([
            'name'        => $name,
            'homepage_id' => $id,
            'path'        => $path,
            'type'        => $type,
            'structure'   => $structure,
        ]);
    }

    public function delete($id)
    {
        $homepage = Homepage::findOrFail($id);
        $homepage->delete();
        Homepage::where('homepage_id', $id)->delete();
    }

    public function deleteRecord($id)
    {
        $record = Homepage::findOrFail($id);


        // Derive these from the record to avoid mismatches
        $homepageId = (int) $record->homepage_id;
        $type       = (string) $record->type;   // e.g. "header"
        $targetId   = (int) $record->id;        // e.g. 14


        if ($this->isReferencedInStructure($homepageId, $type, $targetId, $targetId)) {
            abort(422, "Dieses Element kann nicht gelöscht werden, da es noch wo anders verwendet wird.");
        }

        $record->delete();

        return response()->json(['status' => 'ok']);
    }

    protected function isReferencedInStructure(int $homepageId, string $type, int $targetId, int $selfId): bool
    {
        $path = '$."' . $type . '".id';

        $direct = Homepage::where('homepage_id', $homepageId)
            ->where('id', '!=', $selfId)
            ->whereNotNull('structure')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(structure, ?)) = ?", [$path, (string) $targetId])
            ->exists();

        if ($direct) return true;

        // Deep check inside content array: [{"type": "...","id": ...}]
        return Homepage::where('homepage_id', $homepageId)
            ->where('id', '!=', $selfId)
            ->whereNotNull('structure')
            ->whereRaw("
            EXISTS (
              SELECT 1
              FROM JSON_TABLE(structure->'$.content', '$[*]'
                COLUMNS (
                  ctype VARCHAR(64) PATH '$.type',
                  cid   INT         PATH '$.id'
                )
              ) AS jt
              WHERE jt.ctype = ? AND jt.cid = ?
            )
        ", [$type, $targetId])
            ->exists();
    }
}
