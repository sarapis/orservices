<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $primaryKey = 'contact_recordid';
    
	public $timestamps = false;

	public function organization()
    {
        return $this->belongsTo('App\Organization', 'contact_organizations', 'organization_recordid');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'contact_services', 'service_recordid');
    }

    public function phone()
    {
        return $this->belongsTo('App\Phone', 'contact_phones', 'phone_recordid');
    }
}
