<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class facilityType extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'facility_type', 'created_by', 'deleted_by', 'notes',
    ];
}
