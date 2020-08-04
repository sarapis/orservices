<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'language_recordid', 'language_location', 'language_service', 'language', 'flag',
    ];
}
