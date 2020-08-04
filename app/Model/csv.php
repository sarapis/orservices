<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class csv extends Model
{
    protected $table = 'csv';

    protected $fillable = [
        'name', 'description'
    ];
}
