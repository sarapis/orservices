<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Serviceaddress extends Model
{
    use Sortable;

    protected $table = 'service_address';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
