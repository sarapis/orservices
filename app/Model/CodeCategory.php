<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'created_by'
    ];
}
