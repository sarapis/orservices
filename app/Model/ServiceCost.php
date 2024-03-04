<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceCost extends Model
{
    protected $fillable = [
        'service_recordid', 'cost_recordid'
    ];
}
