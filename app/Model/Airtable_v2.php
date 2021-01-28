<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Airtable_v2 extends Model
{
    protected $fillable = [
        'name', 'records', 'syncdate',
    ];
}
