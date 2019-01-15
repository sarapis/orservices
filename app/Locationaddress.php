<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Locationaddress extends Model
{
    use Sortable;

    protected $table = 'location_address';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
