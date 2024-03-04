<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Airtablekeyinfo extends Model
{
    protected $fillable = [
        'api_key', 'base_url', 'access_token'
    ];
}
