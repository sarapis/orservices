<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LocationLanguage extends Model
{
    protected $fillable = [
        'language_recordid', 'location_recordid'
    ];
}
