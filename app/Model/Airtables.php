<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Airtables extends Model
{
    protected $fillable = [
        'name', 'records', 'syncdate',
    ];
}
