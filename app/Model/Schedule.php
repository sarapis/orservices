<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $primaryKey = 'schedule_recordid';

    protected $fillable = [
        'schedule_recordid', 'schedule_id', 'schedule_services', 'schedule_locations', 'schedule_x_phones', 'schedule_days_of_week', 'schedule_opens_at', 'schedule_closes_at', 'schedule_holiday', 'schedule_start_date', 'schedule_end_date', 'address_locations', 'schedule_closed', 'flag', 'schedule_description',
    ];

    public function services()
    {
        $this->primaryKey = 'schedule_recordid';

        return $this->belongsToMany('App\Model\Service', 'service_schedules', 'schedule_recordid', 'service_recordid');
    }

    public function locations()
    {

        return $this->belongsToMany('App\Model\Location', 'location_schedules', 'schedule_recordid', 'location_recordid');
    }

    public function phone()
    {
        return $this->belongsTo('App\Model\Phone', 'schedule_x_phones', 'phone_recordid');
    }
}
