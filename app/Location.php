<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Location extends Model
{
	use Sortable;

    protected $table = 'locations';

    protected $primaryKey = 'location_recordid';
    
	public $timestamps = false;

	public function organization()
    {
        return $this->belongsTo('App\Organization', 'location_organization', 'organization_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Service', 'service_location', 'location_recordid', 'service_recordid');
    }

    public function phones()
    {

        return $this->belongsToMany('App\Phone', 'location_phone', 'location_recordid', 'phone_recordid');
    }

    public function detail()
    {
        return $this->hasMany('App\Detail', 'detail_locations', 'location_recordid');
    }

    public function schedules()
    {
        return $this->belongsToMany('App\Schedule', 'location_schedule', 'location_recordid', 'schedule_recordid');
    }

    public function address()
    {
        return $this->belongsToMany('App\Address', 'location_address', 'location_recordid', 'address_recordid');
    }

    public function geolocation($latitude, $longitude)
    {
        // $circle_radius = 3959;
        // $max_distance = 2;

        return $cities = Location::select(Location::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance')
            ->get();
   
        // echo "This is a test function";
    }
}
