<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Location extends Model implements ContractsAuditable
{
    protected $primaryKey = 'location_recordid';

    use Auditable;

    protected $auditEvents = [
        'updated',
        'deleted',
        'created',
    ];

    protected $fillable = [
        'location_recordid', 'location_name', 'location_organization', 'location_alternate_name', 'location_transportation', 'location_latitude', 'location_longitude', 'location_description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag', 'updated_by', 'created_by', 'accessibility_recordid', 'accessibility_details', 'location_type', 'location_url', 'external_identifier', 'external_identifier_type', 'location_languages', 'accessesibility_url',
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
        // return $this->hasMany('App\Model\Language', 'language_location', 'location_recordid');
        return $this->belongsToMany('App\Model\Language', 'location_languages', 'location_recordid', 'language_recordid');
    }

    public function accessibilities()
    {
        // return $this->hasMany('App\Model\Accessibility', 'accessibility_location', 'location_recordid');
        return $this->belongsToMany('App\Model\Accessibility', 'location_accessibilities', 'location_recordid', 'accessibility_recordid');
    }

    /**
     * Get the get_accessibility that owns the Location
     */
    public function get_accessibility(): BelongsTo
    {
        return $this->belongsTo(Accessibility::class, 'accessibility_recordid', 'id');
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

        return $cities = Location::select(Location::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance')
            ->get();

        // echo "This is a test function";
    }

    public function regions()
    {
        return $this->belongsToMany('App\Model\Region', 'location_regions', 'location_recordid', 'region_id');
    }

    public function getAddresses()
    {
        $addressArray = [];
        // if ($this->address()->where('is_main', '1')->exists()) {
        //     $address = $this->address()->where('is_main', '1')->first();
        // } else {
        //     $address = $this->address()->first();
        // }
        foreach ($this->address()->get() as $address) {
            $addressData = '';
            if ($address) {
                $addressData .= $address->address_1.' ';
                $addressData .= $address->address_2.' ';
                $addressData .= $address->address_city.' ';
                $addressData .= $address->address_state_province.' ';
                $addressData .= $address->address_postal_code.' ';

                if ($address->address_type_data) {
                    $addressData .= ' - '.$address->address_type_data;
                }
                $addressArray[] = $addressData;
            }
        }

        return $addressArray;
    }
}
