<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{

    protected $primaryKey = 'phone_recordid';

    protected $fillable = [
        'phone_recordid', 'phone_number', 'phone_locations', 'phone_services', 'phone_organizations', 'phone_contacts', 'phone_extension', 'phone_type', 'phone_language', 'phone_description', 'phone_schedule', 'flag'
    ];

    public function locations()
    {
        return $this->belongsToMany('App\Model\Location', 'location_phones', 'phone_recordid', 'location_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Model\Service', 'service_phones', 'phone_recordid', 'service_recordid');
    }

    public function organization()
    {
        return $this->belongsToMany('App\Model\Organization', 'organization_phones', 'phone_recordid', 'organization_recordid');
    }

    public function contact()
    {
        // return $this->hasMany('App\Model\Contact', 'contact_phones', 'phone_recordid');
        return $this->belongsToMany('App\Model\Contact', 'contact_phones', 'phone_recordid', 'contact_recordid');
    }

    public function schedule()
    {
        return $this->belongsTo('App\Model\Schedule', 'phone_schedule', 'schedule_recordid');
    }
}
