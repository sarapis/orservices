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

	public function services()
    {
        $this->primaryKey='schedule_recordid';

        return $this->belongsToMany('App\Service', 'service_schedule', 'schedule_recordid', 'service_recordid');
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
