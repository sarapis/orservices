<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationTag extends Model
{
    protected $fillable = [
        'tag', 'created_by', 'updated_by', 'order'
    ];
}
