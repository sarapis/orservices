<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceContact extends Model
{
    protected $fillable = [
    	'service_recordid','contact_recordid'
    ];
}
