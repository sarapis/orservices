<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $fillable = [
        'search_term', 'search_results', 'times_searched',
    ];
}
