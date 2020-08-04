<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServicePhone extends Model
{
    protected $fillable = [
    	'service_recordid','phone_recordid'
    ];
}
