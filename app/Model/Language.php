<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'language_recordid', 'language_location', 'language_service', 'language', 'flag', 'order', 'code', 'note'
    ];
    public function service()
    {
        return $this->belongsTo('App\Model\Service', 'language_service', 'service_recordid');
    }
    public function location()
    {
        return $this->belongsTo('App\Model\Location', 'language_location', 'location_recordid');
    }
}
