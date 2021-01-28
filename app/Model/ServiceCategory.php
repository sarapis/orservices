<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'term', 'created_by', 'updated_by'
    ];
}
