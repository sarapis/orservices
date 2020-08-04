<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LocationAddress extends Model
{
    protected $fillable = [
    	'location_recordid','address_recordid'
    ];
}
