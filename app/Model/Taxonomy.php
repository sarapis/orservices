<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $primaryKey = 'taxonomy_recordid';

    protected $fillable = [
        'taxonomy_recordid', 'taxonomy_name', 'taxonomy_parent_name', 'taxonomy_grandparent_name', 'taxonomy_vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'taxonomy_parent_recordid', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag', 'category_logo', 'category_logo_white', 'exclude_vocabulary', 'badge_color', 'order'
    ];
    public function childs()
    {
        return $this->hasMany('App\Model\Taxonomy', 'taxonomy_parent_name', 'taxonomy_recordid');
    }

    public function parent()
    {
        return $this->belongsTo('App\Model\Taxonomy', 'taxonomy_parent_name', 'taxonomy_recordid');
    }

    public function service()
    {

        return $this->belongsToMany('App\Model\Service', 'service_taxonomies', 'taxonomy_recordid', 'service_recordid');
    }

    public function alt_taxonomies()
    {
        return $this->belongsToMany('App\Model\Alt_taxonomy', 'alt_taxonomies_term_relation');
    }
}
