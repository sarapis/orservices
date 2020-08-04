<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceTaxonomy extends Model
{
    protected $fillable = [
    	'service_recordid','taxonomy_recordid'
    ];
}
