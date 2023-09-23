<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LocationAccessibility extends Model
{
    protected $fillable = [
        'location_recordid', 'accessibility_recordid'
    ];
}
