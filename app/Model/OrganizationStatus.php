<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationStatus extends Model
{
    protected $fillable = ['status', 'created_by', 'updated_by', 'order'];
}
