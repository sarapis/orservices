<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceStatus extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status', 'created_by', 'updated_by'
    ];
}
