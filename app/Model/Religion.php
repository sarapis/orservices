<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'notes', 'type', 'parent', 'organizations', 'icon',
    ];
}
