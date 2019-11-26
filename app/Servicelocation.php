<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Servicelocation extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'service_location';
    
	public $timestamps = false;

}
