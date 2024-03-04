<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationFunding extends Model
{
    protected $fillable = [
        'funding_recordid', 'organization_recordid'
    ];
}
