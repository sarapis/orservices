<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Servicephone extends Model
{
    use Sortable;

    protected $table = 'service_phone';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
