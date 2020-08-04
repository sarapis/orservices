<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    protected $fillable = [
    	'service_recordid','schedule_recordid'
    ];
}
