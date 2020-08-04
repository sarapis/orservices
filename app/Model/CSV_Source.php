<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CSV_Source extends Model
{
    protected $fillable = [
        'name', 'source', 'records', 'syncdate',
    ];
}
