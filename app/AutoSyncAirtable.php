<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutoSyncAirtable extends Model
{
    protected $table = 'auto_sync';
	public $timestamps = false;
}
