<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Servicedetail extends Model
{
    use Sortable;

    protected $table = 'service_detail';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
