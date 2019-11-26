<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AltTaxonomiesTermRelation extends Model
{
    use Sortable;
    protected $primaryKey = 'id';
    protected $table = 'alt_taxonomies_term_relation';
    public $timestamps = false;
}
