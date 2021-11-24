<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LocationRegion extends Model
{
    protected $fillable = [
        'region_id', 'location_recordid'
    ];

    /**
     * The location that belong to the LocationRegion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'location_regions', 'location_recordid', 'region_id');
    }
}
