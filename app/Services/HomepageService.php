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
}
