<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Servicetaxonomy extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'service_taxonomy';
    
	public $timestamps = false;

}
