<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phones';

    protected $primaryKey = 'phone_recordid';
    
	public $timestamps = false;

    public function locations()
    {
        return $this->belongsToMany('App\Location', 'location_phone', 'phone_recordid', 'location_recordid');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'phone_services', 'service_recordid');
    }

    public function schedule()
    {
        return $this->belongsTo('App\Schedule', 'phone_schedule', 'schedule_recordid');
    }
}
