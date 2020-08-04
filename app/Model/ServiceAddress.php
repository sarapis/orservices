<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceAddress extends Model
{
    protected $fillable = [
    	'service_recordid','address_recordid'
    ];
}
