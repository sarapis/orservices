<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Serviceschedule extends Model
{
    use Sortable;

    protected $table = 'service_schedule';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
