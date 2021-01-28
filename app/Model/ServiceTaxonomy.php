<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceTaxonomy extends Model
{
    protected $fillable = [
        'service_recordid', 'taxonomy_recordid'
    ];

    public function service()
    {
        return $this->belongsTo('App\Model\Service', 'service_recordid', 'service_recordid');
    }
    public function taxonomy()
    {
        return $this->belongsTo('App\Model\Taxonomy', 'taxonomy_recordid', 'taxonomy_recordid');
    }
}
