<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationHistory extends Model
{
    protected $fillable = [
        'organization_recordid', 'changed_fieldname', 'old_value', 'new_value', 'changed_by'
    ];
}
