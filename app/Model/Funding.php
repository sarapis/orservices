<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Funding extends Model
{

    protected $fillable = [
        'funding_recordid', 'source', 'organizations', 'services', 'attributes'
    ];

    public function services()
    {
        $this->primaryKey = 'funding_recordid';
        return $this->belongsToMany('App\Model\Service', 'service_fundings', 'funding_recordid', 'service_recordid');
    }

    public function organizationsData()
    {
        $this->primaryKey = 'funding_recordid';
        return $this->belongsToMany('App\Model\Organization', 'organization_fundings', 'funding_recordid', 'organization_recordid');
    }
}
