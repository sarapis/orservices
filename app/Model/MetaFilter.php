<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MetaFilter extends Model
{
    protected $fillable = [
    	'operations','facet','method','values'
    ];
}
