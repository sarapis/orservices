<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationProgram extends Model
{
    protected $fillable = [
        'organization_recordid', 'program_recordid',
    ];
}
