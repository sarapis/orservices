<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OtherAttribute extends Model
{
    protected $fillable = [
        'link_id', 'link_type', 'taxonomy_term_id'
    ];
}
