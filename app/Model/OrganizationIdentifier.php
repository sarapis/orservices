<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationIdentifier extends Model
{
    protected $fillable = [
        'organization_recordid', 'identifier_recordid'
    ];
}
