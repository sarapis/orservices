<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AutoSyncAirtable extends Model
{
    protected $fillable = [
        'option', 'days', 'working_status',
    ];
}
