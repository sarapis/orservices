<?php

namespace App\Imports;

use App\Model\Taxonomy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaxonomyImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $taxonomy = Taxonomy::where('taxonomy_id', $row['id'])->first();
        $array = [];
        if (!isset($taxonomy->taxonomy_id)) {
            $array = [
                'taxonomy_recordid' => Taxonomy::select('taxonomy_recordid')->max('taxonomy_recordid') + 1,
                'taxonomy_id' => $row['id'],
                'category_id' => $row['id'],
                'taxonomy_name' => $row['name'],
                'taxonomy_facet' => $row['taxonomy_facet'],
                'taxonomy_parent_recordid' => $row['parent_id'],
                'taxonomy_parent_name' => $row['parent_name'],
                'taxonomy_vocabulary' => $row['vocabulary'],
            ];
        } else {
            $taxonomy->taxonomy_recordid = $taxonomy->taxonomy_recordid;

            $taxonomy->taxonomy_id = $row['id'] != 'NULL' ? $row['id'] : null;
            $taxonomy->category_id = $row['id'] != 'NULL' ? $row['id'] : null;
            $taxonomy->taxonomy_name = $row['name'] != 'NULL' ? $row['name'] : null;
            $taxonomy->taxonomy_facet = $row['taxonomy_facet'] != 'NULL' ? $row['taxonomy_facet'] : null;
            $taxonomy->taxonomy_parent_recordid = $row['parent_id'] != 'NULL' ? $row['parent_id'] : null;
            $taxonomy->taxonomy_parent_name = $row['parent_name'] != 'NULL' ? $row['parent_name'] : null;
            $taxonomy->taxonomy_vocabulary = $row['vocabulary'] != 'NULL' ? $row['vocabulary'] : null;

            $taxonomy->save();
        }
        return new Taxonomy($array);
    }
}
