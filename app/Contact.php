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

        $this->primaryKey='contact_recordid';

        return $this->belongsToMany('App\Service', 'service_contact', 'contact_recordid', 'service_recordid');
    }

    public function phone()
    {
        return $this->belongsTo('App\Phone', 'contact_phones', 'phone_recordid');
    }
}
