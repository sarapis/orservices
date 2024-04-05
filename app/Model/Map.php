<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $fillable = [
        'name', 'sku', 'geocode_map_key', 'state', 'lat', 'long', 'active', 'zoom', 'zoom_profile', 'javascript_map_key', 'distance_radius',
    ];
}
