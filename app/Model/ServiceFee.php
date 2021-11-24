<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceFee extends Model
{
    protected $fillable = [
        'service_recordid', 'fees_id'
    ];
}
