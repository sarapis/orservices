<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Locationphone extends Model
{
    use Sortable;

    protected $table = 'location_phone';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
