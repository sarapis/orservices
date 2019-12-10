<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Serviceorganization extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'service_organization';
    
	public $timestamps = false;

}
