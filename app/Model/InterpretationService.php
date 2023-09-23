<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterpretationService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'created_by', 'updated_by'
    ];
}
