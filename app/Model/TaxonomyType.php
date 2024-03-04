<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaxonomyType extends Model
{
    protected $fillable = [
        'name', 'type', 'reference_url', 'notes', 'taxonomy_type_recordid', 'order', 'version'
    ];
    protected $primaryKey = 'taxonomy_type_recordid';

    public function taxonomy_term()
    {
        return $this->belongsToMany('App\Model\Taxonomy', 'taxonomy_terms', 'taxonomy_type_recordid', 'taxonomy_recordid');
    }
    public function additional_taxonomy_term()
    {
        return $this->belongsToMany('App\Model\Taxonomy', 'additional_taxonomies', 'taxonomy_type_recordid', 'taxonomy_recordid');
    }
}
