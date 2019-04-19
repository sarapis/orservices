<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $primaryKey = 'organization_recordid';
    
	public $timestamps = false;

	public function services()
    {
        return $this->hasMany('App\Service', 'service_organization', 'organization_recordid');
        
    }

    public function phones()
    {
        return $this->hasmany('App\Phone', 'phone_organizations', 'organization_recordid');
    }

    public function location()
    {
        return $this->hasmany('App\Location', 'location_organization', 'organization_recordid');
    }

    public function contact()
    {
        return $this->hasmany('App\Contact', 'contact_organizations', 'organization_recordid');
    }

    public function detail()
    {
        return $this->hasmany('App\Detail', 'detail_organizations', 'organization_recordid');
    }
}
