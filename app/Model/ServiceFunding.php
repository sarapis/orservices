<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceFunding extends Model
{
    protected $fillable = [
        'funding_recordid', 'service_recordid'
    ];
}
