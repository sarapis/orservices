<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tag', 'created_by', 'updated_by', 'id'
    ];
}
