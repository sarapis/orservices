<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey = 'address_recordid';

    protected $fillable = [
        'address_recordid', 'address_1', 'address_2', 'address_city', 'address_state_province', 'address_postal_code', 'address_region', 'address_country', 'address_attention', 'address_type', 'address_locations', 'address_services', 'address_organization', 'flag'
    ];

    public function locations()
    {
        return $this->belongsToMany('App\Model\Location', 'location_addresses', 'address_recordid', 'location_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Model\Service', 'service_addresses', 'address_recordid', 'service_recordid');
    }
}
