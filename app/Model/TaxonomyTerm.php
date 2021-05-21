<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaxonomyTerm extends Model
{
    protected $fillable = ['taxonomy_recordid', 'taxonomy_type_recordid'];
}
