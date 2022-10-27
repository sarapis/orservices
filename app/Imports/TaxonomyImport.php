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
        $taxonomy = Taxonomy::where('taxonomy_recordid', $row['taxonomy_recordid'])->first();
        $array = [];
        if (!isset($taxonomy->taxonomy_recordid)) {
            $array = [
                'taxonomy_recordid' => isset($row['taxonomy_recordid']) ? $row['taxonomy_recordid'] : null,
                'taxonomy_name' => isset($row['taxonomy_name']) ? $row['taxonomy_name'] : null,
                'taxonomy_parent_name' => isset($row['taxonomy_parent_name']) ? $row['taxonomy_parent_name'] : null,
                'exclude_vocabulary' => isset($row['exclude_vocabulary']) ? $row['exclude_vocabulary'] : null,
                'taxonomy' => isset($row['taxonomy']) ? $row['taxonomy'] : null,
                'category_logo' => isset($row['category_logo']) ? $row['category_logo'] : null,
                'category_logo_white' => isset($row['category_logo_white']) ? $row['category_logo_white'] : null,
                'taxonomy_grandparent_name' => isset($row['taxonomy_grandparent_name']) ? $row['taxonomy_grandparent_name'] : null,
                'taxonomy_vocabulary' => isset($row['taxonomy_vocabulary']) ? $row['taxonomy_vocabulary'] : null,
                'taxonomy_x_description' => isset($row['taxonomy_x_description']) ? $row['taxonomy_x_description'] : null,
                'taxonomy_x_notes' => isset($row['taxonomy_x_notes']) ? $row['taxonomy_x_notes'] : null,
                'language' => isset($row['language']) ? $row['language'] : null,
                'taxonomy_services' => isset($row['taxonomy_services']) ? $row['taxonomy_services'] : null,
                'taxonomy_parent_recordid' => isset($row['taxonomy_parent_recordid']) ? $row['taxonomy_parent_recordid'] : null,
                'taxonomy_facet' => isset($row['taxonomy_facet']) ? $row['taxonomy_facet'] : null,
                'category_id' => isset($row['category_id']) ? $row['category_id'] : null,
                'taxonomy_id' => isset($row['taxonomy_id']) ? $row['taxonomy_id'] : null,
                'order' => isset($row['order']) ? $row['order'] : null,
                'badge_color' => isset($row['badge_color']) ? $row['badge_color'] : null,
                'flag' => isset($row['flag']) ? $row['flag'] : null,
                'status' => isset($row['status']) ? $row['status'] : null,
            ];
        } else {
            $taxonomy->taxonomy_recordid = $taxonomy->taxonomy_recordid;
            $taxonomy->taxonomy_name = isset($row['taxonomy_name']) ? $row['taxonomy_name'] : null;
            $taxonomy->taxonomy_parent_name = isset($row['taxonomy_parent_name']) ? $row['taxonomy_parent_name'] : null;
            $taxonomy->exclude_vocabulary = isset($row['exclude_vocabulary']) ? $row['exclude_vocabulary'] : null;
            $taxonomy->taxonomy = isset($row['taxonomy']) ? $row['taxonomy'] : null;
            $taxonomy->category_logo = isset($row['category_logo']) ? $row['category_logo'] : null;
            $taxonomy->category_logo_white = isset($row['category_logo_white']) ? $row['category_logo_white'] : null;
            $taxonomy->taxonomy_grandparent_name = isset($row['taxonomy_grandparent_name']) ? $row['taxonomy_grandparent_name'] : null;
            $taxonomy->taxonomy_vocabulary = isset($row['taxonomy_vocabulary']) ? $row['taxonomy_vocabulary'] : null;
            $taxonomy->taxonomy_x_description = isset($row['taxonomy_x_description']) ? $row['taxonomy_x_description'] : null;
            $taxonomy->taxonomy_x_notes = isset($row['taxonomy_x_notes']) ? $row['taxonomy_x_notes'] : null;
            $taxonomy->language = isset($row['language']) ? $row['language'] : null;
            $taxonomy->taxonomy_services = isset($row['taxonomy_services']) ? $row['taxonomy_services'] : null;
            $taxonomy->taxonomy_parent_recordid = isset($row['taxonomy_parent_recordid']) ? $row['taxonomy_parent_recordid'] : null;
            $taxonomy->taxonomy_facet = isset($row['taxonomy_facet']) ? $row['taxonomy_facet'] : null;
            $taxonomy->category_id = isset($row['category_id']) ? $row['category_id'] : null;
            $taxonomy->taxonomy_id = isset($row['taxonomy_id']) ? $row['taxonomy_id'] : null;
            $taxonomy->order = isset($row['order']) ? $row['order'] : null;
            $taxonomy->badge_color = isset($row['badge_color']) ? $row['badge_color'] : null;
            $taxonomy->flag = isset($row['flag']) ? $row['flag'] : null;
            $taxonomy->status = isset($row['status']) ? $row['status'] : null;

            $taxonomy->save();
        }
        return new Taxonomy($array);
    }
    // public function model(array $row)
    // {
    //     $taxonomy = Taxonomy::where('taxonomy_recordid', $row['id'])->first();
    //     $array = [];
    //     // $taxonomy_types = TaxonomyType::where('name', $row['taxonomy'])->first();
    //     $taxonomy_type = TaxonomyType::firstOrCreate(
    //         ['name' => $row['taxonomy']],
    //         [
    //             'name' => $row['taxonomy'],
    //             'type' => 'internal',
    //             'taxonomy_type_recordid' => TaxonomyType::max('taxonomy_type_recordid') + 1
    //         ]
    //     );
    //     if (!isset($taxonomy->taxonomy_recordid)) {
    //         $array = [
    //             'taxonomy_recordid' => Taxonomy::select('taxonomy_recordid')->max('taxonomy_recordid') + 1,
    //             // 'taxonomy_id' => $row['id'],
    //             // 'category_id' => $row['id'],
    //             'taxonomy_name' => $row['term'],
    //             'taxonomy_x_description' => $row['description'],
    //             // 'taxonomy_facet' => $row['taxonomy_facet'],
    //             // 'taxonomy_parent_recordid' => $row['parent_id'],
    //             'taxonomy_parent_name' => $row['parent_id'],
    //             'taxonomy' => $taxonomy_type ? $taxonomy_type->taxonomy_type_recordid : '',
    //             'x_taxonomies' => $row['x_taxonomies'],
    //             'taxonomy_x_notes' => $row['taxonomy_x_notes'],
    //             'badge_color' => $row['badge_color'],
    //             'language' => $row['language'],
    //             // 'taxonomy_vocabulary' => $row['vocabulary'],
    //         ];
    //     } else {
    //         $taxonomy->taxonomy_recordid = $taxonomy->taxonomy_recordid;

    //         // $taxonomy->taxonomy_id = $row['id'] != 'NULL' ? $row['id'] : null;
    //         // $taxonomy->category_id = $row['id'] != 'NULL' ? $row['id'] : null;
    //         $taxonomy->taxonomy_name = $row['term'] != 'NULL' ? $row['term'] : null;
    //         // $taxonomy->taxonomy_facet = $row['taxonomy_facet'] != 'NULL' ? $row['taxonomy_facet'] : null;
    //         // $taxonomy->taxonomy_parent_recordid = $row['parent_id'] != 'NULL' ? $row['parent_id'] : null;
    //         $taxonomy->taxonomy_parent_name = $row['parent_id'] != 'NULL' ? $row['parent_id'] : null;
    //         $taxonomy->taxonomy = $taxonomy_type ? $taxonomy_type->taxonomy_type_recordid : null;
    //         $taxonomy->x_taxonomies = $row['x_taxonomies'] != 'NULL' ? $row['x_taxonomies'] : null;
    //         $taxonomy->taxonomy_x_notes = $row['taxonomy_x_notes'] != 'NULL' ? $row['taxonomy_x_notes'] : null;
    //         $taxonomy->badge_color = $row['badge_color'] != 'NULL' ? $row['badge_color'] : null;
    //         $taxonomy->language = $row['language'] != 'NULL' ? $row['language'] : null;
    //         // $taxonomy->taxonomy_vocabulary = $row['vocabulary'] != 'NULL' ? $row['vocabulary'] : null;

    //         $taxonomy->save();
    //     }
    //     return new Taxonomy($array);
    // }
}
