<?php

namespace App\Exports\zip;

use App\Model\Taxonomy;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TaxonomyZipExport implements FromView, WithCustomCsvSettings
{
    public $enclosure;
    public function __construct($enclosure)
    {
        $this->enclosure = $enclosure;
    }
    /**
     * @return View
     */
    public function view(): View
    {
        $taxonomy_terms = Taxonomy::cursor();
        return view('exports.zip.taxonomy_terms', [
            'taxonomy_terms' => $taxonomy_terms,
        ]);
    }
    public function getCsvSettings(): array
    {
        if ($this->enclosure) {
            return [
                'delimiter' => ',',
                'enclosure' => '',
            ];
        }
        return [];
    }
}
