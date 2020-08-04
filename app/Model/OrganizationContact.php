<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationContact extends Model
{
    protected $fillable = [
        'organization_recordid','contact_recordid'
    ];
}
