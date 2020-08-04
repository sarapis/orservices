<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceOrganization extends Model
{
    protected $fillable = [
    	'service_recordid','organization_recordid'
    ];
}
