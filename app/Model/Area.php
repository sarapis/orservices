<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $primaryKey = 'area_recordid';

    protected $fillable = [
        'area_recordid', 'area_service', 'area_description', 'area_date_added', 'area_multiple_counties'
    ];

    // public $timestamps = false;

    public function services()
    {
        return $this->belongsTo('App\Model\Service', 'area_service', 'service_recordid');
    }
}
