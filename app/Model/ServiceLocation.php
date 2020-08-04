<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceLocation extends Model
{
    protected $fillable = [
    	'service_recordid','location_recordid'
    ];
}
