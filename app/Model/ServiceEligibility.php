<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceEligibility extends Model
{
    protected $fillable = [
        'term', 'created_by', 'updated_by'
    ];
}
