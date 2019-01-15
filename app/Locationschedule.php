<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Locationschedule extends Model
{
    use Sortable;

    protected $table = 'location_schedule';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
