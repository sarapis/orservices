<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailType extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'type', 'created_by', 'updated_by', 'order'
    ];
}
