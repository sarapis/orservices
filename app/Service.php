<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Service extends Model
{
    use Sortable;

    protected $table = 'services';

    protected $primaryKey = 'service_recordid';
    
	public $timestamps = false;

    // public function organizations()
    // {
    //     $this->primaryKey='service_recordid';

    //     return $this->belongsToMany('App\Organization', 'service_organization', 'service_recordid', 'organization_recordid');

    // }

    public function organizations()
    {
        $this->primaryKey='service_recordid';
        return $this->belongsTo('App\Organization', 'service_organization', 'organization_recordid');
        
    }

    public function locations()
    {
        $this->primaryKey='service_recordid';

        return $this->belongsToMany('App\Location', 'service_location', 'service_recordid', 'location_recordid');

    }

    public function details()
    {

        return $this->belongsToMany('App\Detail', 'service_detail', 'service_recordid', 'detail_recordid');
    }

    public function taxonomy()
    {
        return $this->belongsToMany('App\Taxonomy', 'service_taxonomy', 'service_recordid', 'taxonomy_id');

    }

    public function phone()
    {
       
        return $this->belongsToMany('App\Phone', 'service_phone', 'service_recordid', 'phone_recordid');

    }

    public function schedules()
    {
        $this->primaryKey='service_recordid';

        return $this->belongsToMany('App\Schedule', 'service_schedule', 'service_recordid', 'schedule_recordid');
    }

    public function contact()
    {

        // $this->primaryKey='service_recordid';

        return $this->belongsToMany('App\Contact', 'service_contact', 'service_recordid', 'contact_recordid');
    }


    public function languages()
    {
        return $this->hasMany('App\Language', 'language_service', 'service_recordid');
    }

    public function address()
    {
        $this->primaryKey='service_recordid';

        return $this->belongsToMany('App\Address', 'service_address', 'service_recordid', 'address_recordid');
    }
}
