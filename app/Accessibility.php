<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accessibility extends Model
{
    protected $table = 'accessibilities';
    
	public $timestamps = false;

	public function location()
    {
        return $this->belongsTo('App\Location', 'accessibility_location', 'location_recordid');
    }
}
