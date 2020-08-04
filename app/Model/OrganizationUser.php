<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    protected $fillable = [
        'organization_recordid', 'user_id',
    ];
}
