<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportConfiguration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'filter', 'endpoint', 'type', 'organization_tags', 'key', 'file_path', 'file_name', 'full_path_name', 'created_by', 'updated_by', 'service_tags'
    ];
}
