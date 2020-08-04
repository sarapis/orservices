<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Alt_taxonomy extends Model
{
    protected $fillable = [
        'alt_taxonomy_name', 'alt_taxonomy_vocabulary',
    ];
}
