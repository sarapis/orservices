<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Alt_taxonomy extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'alt_taxonomies';
    
	public $timestamps = false;

	public function terms() {
		return $this->belongsToMany('App\Taxonomy', 'alt_taxonomies_term_relation');
	}

}
