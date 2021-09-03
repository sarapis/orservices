<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Helptext extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_classification', 'service_conditions', 'service_goals', 'service_activities', 'created_by', 'updated_by'
    ];
}
