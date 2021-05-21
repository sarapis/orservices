<?php

namespace App\Exports;

use App\Model\Taxonomy;
use App\Model\TaxonomyType;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class TaxonomyExport implements FromView
{
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $taxonomies = Taxonomy::select('*');
        $extraData = $this->request->extraData;
        if (isset($extraData['id']) && $extraData['id']) {
            $taxonomies->where('id', $extraData['id']);
        }
        if (isset($extraData['taxonomy_select']) && $extraData['taxonomy_select']) {
            $taxonomyType = TaxonomyType::where('name', $extraData['taxonomy_select'])->first();
            if ($taxonomyType) {
                $taxonomies->where('taxonomy', $taxonomyType->taxonomy_type_recordid);
            }
        }
        if (isset($extraData['parent_filter']) && $extraData['parent_filter']) {
            $parentData = Taxonomy::whereIn('taxonomy_name', $extraData['parent_filter'])->pluck('taxonomy_recordid')->toArray();
            if (in_array('all', $parentData)) {
                $taxonomies->whereNotNull('taxonomy_parent_name');
            } else if (in_array('nont', $parentData)) {
                $taxonomies->whereNull('taxonomy_parent_name');
            } else {
                $taxonomies->whereIn('taxonomy_parent_name', $parentData);
            }
        }
        return view('exports.taxonomies', [
            'taxonomies' => $taxonomies->cursor(),
        ]);
    }
}
