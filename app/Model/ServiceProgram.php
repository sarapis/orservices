<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceProgram extends Model
{
    protected $fillable = [
        'service_recordid', 'program_recordid'
    ];
}
