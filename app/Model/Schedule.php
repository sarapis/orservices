<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Schedule extends Model implements ContractsAuditable
{
    use Auditable;
    protected $primaryKey = 'schedule_recordid';

    protected $fillable = [
        'schedule_recordid', 'name', 'services', 'phones', 'locations', 'weekday', 'byday', 'opens_at', 'opens', 'closes_at', 'closes', 'dtstart', 'until', 'special', 'closed', 'service_at_location', 'freq', 'valid_from', 'valid_to', 'wkst', 'interval', 'count', 'byweekno', 'bymonthday', 'byyearday', 'description', 'timezone', 'schedule_services', 'schedule_locations', 'schedule_holiday', 'schedule_closed', 'open_24_hours', 'notes', 'attending_type', 'schedule_link'
    ];

    public function get_services()
    {
        $this->primaryKey = 'schedule_recordid';

        return $this->belongsToMany('App\Model\Service', 'service_schedules', 'schedule_recordid', 'service_recordid');
    }

    public function get_locations()
    {

        return $this->belongsToMany('App\Model\Location', 'location_schedules', 'schedule_recordid', 'location_recordid');
    }

    public function phone()
    {
        return $this->belongsTo('App\Model\Phone', 'phones', 'phone_recordid');
    }
}
