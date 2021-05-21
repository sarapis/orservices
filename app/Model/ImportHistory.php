<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    protected $fillable = [
        'source_name', 'import_type', 'auto_sync', 'status', 'sync_by', 'error_message'
    ];
}
