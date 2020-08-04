<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $fillable = [
        'recordid', 'agency_code', 'agency_name', 'projects', 'website', 'contacts', 'flag',
    ];
}
