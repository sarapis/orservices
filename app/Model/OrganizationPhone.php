<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationPhone extends Model
{
    protected $fillable = [
        'organization_recordid', 'phone_recordid',
    ];
}
