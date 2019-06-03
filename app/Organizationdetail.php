<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Organizationdetail extends Model
{
    use Sortable;

    protected $table = 'organization_detail';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
