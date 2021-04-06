<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationTag extends Model
{
    protected $fillable = [
        'tag', 'created_by1', 'updated_by'
    ];
}
