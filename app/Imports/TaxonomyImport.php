<?php

namespace App\Imports;

use App\Model\Taxonomy;
use App\Model\TaxonomyType;
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
        $taxonomy = Taxonomy::where('taxonomy_recordid', $row['id'])->first();
        $array = [];
        $taxonomy_types = TaxonomyType::where('name', $row['taxonomy'])->first();
        if (!isset($taxonomy->taxonomy_recordid)) {
            $array = [
                'taxonomy_recordid' => Taxonomy::select('taxonomy_recordid')->max('taxonomy_recordid') + 1,
                // 'taxonomy_id' => $row['id'],
                // 'category_id' => $row['id'],
                'taxonomy_name' => $row['term'],
                'taxonomy_x_description' => $row['description'],
                // 'taxonomy_facet' => $row['taxonomy_facet'],
                // 'taxonomy_parent_recordid' => $row['parent_id'],
                'taxonomy_parent_name' => $row['parent_id'],
                'taxonomy' => $taxonomy_types ? $taxonomy_types->taxonomy_type_recordid : '',
                'x_taxonomies' => $row['x_taxonomies'],
                'taxonomy_x_notes' => $row['taxonomy_x_notes'],
                'badge_color' => $row['badge_color'],
                'language' => $row['language'],
                // 'taxonomy_vocabulary' => $row['vocabulary'],
            ];
        } else {
            $taxonomy->taxonomy_recordid = $taxonomy->taxonomy_recordid;

            // $taxonomy->taxonomy_id = $row['id'] != 'NULL' ? $row['id'] : null;
            // $taxonomy->category_id = $row['id'] != 'NULL' ? $row['id'] : null;
            $taxonomy->taxonomy_name = $row['term'] != 'NULL' ? $row['term'] : null;
            // $taxonomy->taxonomy_facet = $row['taxonomy_facet'] != 'NULL' ? $row['taxonomy_facet'] : null;
            // $taxonomy->taxonomy_parent_recordid = $row['parent_id'] != 'NULL' ? $row['parent_id'] : null;
            $taxonomy->taxonomy_parent_name = $row['parent_id'] != 'NULL' ? $row['parent_id'] : null;
            $taxonomy->taxonomy = $taxonomy_types ? $taxonomy_types->taxonomy_type_recordid : null;
            $taxonomy->x_taxonomies = $row['x_taxonomies'] != 'NULL' ? $row['x_taxonomies'] : null;
            $taxonomy->taxonomy_x_notes = $row['taxonomy_x_notes'] != 'NULL' ? $row['taxonomy_x_notes'] : null;
            $taxonomy->badge_color = $row['badge_color'] != 'NULL' ? $row['badge_color'] : null;
            $taxonomy->language = $row['language'] != 'NULL' ? $row['language'] : null;
            // $taxonomy->taxonomy_vocabulary = $row['vocabulary'] != 'NULL' ? $row['vocabulary'] : null;

            $taxonomy->save();
        }
        return new Taxonomy($array);
    }
}
