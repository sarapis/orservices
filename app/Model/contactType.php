<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class contactType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contact_type', 'created_by', 'deleted_by', 'notes',
    ];
}
