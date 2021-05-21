<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxonomyEmail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'email_recordid', 'email', 'created_by'
    ];
}
