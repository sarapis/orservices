<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $table = 'taxonomy';

    protected $primaryKey = 'taxonomy_id';

    public $fillable = ['name','parent_name'];

    public $timestamps = false;

    /**
     * Get the index name for the model.
     *
     * @return string
    */
    public function childs() {
        return $this->hasMany('App\Taxonomy','taxonomy_parent_name','taxonomy_name') ;
    }

    public function parent()
    {
        return $this->belongsTo('App\Taxonomy', 'taxonomy_parent_name', 'taxonomy_name');
    }

    public function service()
    {
        $this->primaryKey='taxonomy_id';
        
        return $this->belongsToMany('App\Service', 'service_taxonomy', 'taxonomy_id', 'service_recordid');
    }

    public function alt_taxonomies()
    {
        return $this->belongsToMany('App\Alt_taxonomy', 'alt_taxonomies_term_relation');
    }
}
