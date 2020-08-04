<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $primaryKey = 'detail_recordid';

    protected $fillable = [
        'detail_recordid', 'detail_value', 'detail_type', 'detail_description', 'detail_organizations', 'detail_services', 'detail_locations', 'flag',
    ];

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'detail_organizations', 'organization_recordid');
    }

    public function location()
    {
        return $this->hasMany('App\Model\Location', 'location_recordid', 'detail_locations');
    }
}
