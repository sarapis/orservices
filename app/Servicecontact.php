<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Servicecontact extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'service_contact';
    
	public $timestamps = false;

}
