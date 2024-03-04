<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Identifier extends Model
{
    protected $fillable = [
        'identifier_recordid', 'identifier', 'identifier_scheme', 'identifier_type', 'organizations', 'created_by'
    ];

    public function organizations()
    {
        $this->primaryKey = 'organization_recordid';
        return $this->belongsToMany('App\Model\Organization', 'organization_identifiers', 'identifier_recordid', 'organization_recordid');
    }
}
