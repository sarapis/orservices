<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LocationPhone extends Model
{
    protected $fillable = [
    	'location_recordid','phone_recordid'
    ];
}
