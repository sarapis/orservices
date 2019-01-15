<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Schedule extends Model
{
	use Sortable;

    protected $table = 'schedule';

    protected $primaryKey = 'schedule_recordid';
    
	public $timestamps = false;

	public function service()
    {
        return $this->belongsTo('App\Service', 'schedule_services', 'service_recordid');
    }

    public function locations()
    {

        return $this->belongsToMany('App\Location', 'location_schedule', 'schedule_recordid', 'location_recordid');

    }

    public function phone()
    {
        return $this->belongsTo('App\Phone', 'schedule_x_phones', 'phone_recordid');
    }
}
