<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationTableFilter extends Model
{
    protected $fillable = [
        'user_id',
        'filter_name',
        'organization_tags',
        'service_tags',
        'status',
        'bookmark_only',
        'start_date',
        'end_date',
        'start_verified',
        'end_verified',
        'start_updated',
        'end_updated', 'last_updated_by', 'last_verified_by'
    ];
}
