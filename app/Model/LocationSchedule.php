<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LocationSchedule extends Model
{
    protected $fillable = [
    	'location_recordid','schedule_recordid'
    ];
}
