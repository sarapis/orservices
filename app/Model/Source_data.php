<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Source_data extends Model
{
    protected $fillable = [
        'source_name', 'active',
    ];
}
