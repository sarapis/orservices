<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Accessibility extends Model
{
    protected $fillable = [
        'accessibility_recordid', 'accessibility_location', 'accessibility', 'accessibility_details'
    ];

    public function location()
    {
        return $this->belongsTo('App\Model\Location', 'accessibility_location', 'location_recordid');
    }
}
