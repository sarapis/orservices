<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AreaService extends Model
{
    protected $fillable = [
        'service_recordid', 'service_area_id'
    ];
}
