<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Location extends Model implements ContractsAuditable
{
    protected $primaryKey = 'location_recordid';

    use Auditable;
    protected $auditEvents = [
        'updated',
        'deleted',
    ];

    protected $fillable = [
        'location_recordid', 'location_name', 'location_organization', 'location_alternate_name', 'location_transportation', 'location_latitude', 'location_longitude', 'location_description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag',
    ];

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'location_organization', 'organization_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Model\Service', 'service_locations', 'location_recordid', 'service_recordid');
    }

    public function phones()
    {

        return $this->belongsToMany('App\Model\Phone', 'location_phones', 'location_recordid', 'phone_recordid');
    }

    public function detail()
    {
        return $this->hasMany('App\Model\Detail', 'detail_locations', 'location_recordid');
    }

    public function languages()
    {
        return $this->hasMany('App\Model\Language', 'language_location', 'location_recordid');
    }

    public function accessibilities()
    {
        return $this->hasMany('App\Model\Accessibility', 'accessibility_location', 'location_recordid');
    }

    public function schedules()
    {
        return $this->belongsToMany('App\Model\Schedule', 'location_schedules', 'location_recordid', 'schedule_recordid');
    }

    public function address()
    {
        return $this->belongsToMany('App\Model\Address', 'location_addresses', 'location_recordid', 'address_recordid');
    }

    public function geolocation($latitude, $longitude)
    {
        // $circle_radius = 3959;
        // $max_distance = 2;

        return $cities = Location::select(Location::raw('*, ( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance')
            ->get();

        // echo "This is a test function";
    }
}
