<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    protected $fillable = [
        'organization_type', 'notes', 'created_by', 'deleted_by',
    ];
}
