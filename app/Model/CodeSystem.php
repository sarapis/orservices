<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeSystem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'url', 'versions', 'created_by', 'oid'
    ];
}
