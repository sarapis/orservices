<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Area extends Model
{
    use Sortable;

    protected $table = 'areas';

    protected $primaryKey = 'area_recordid';
    
	public $timestamps = false;

	public function services()
    {
        return $this->belongsTo('App\Service', 'area_service', 'service_recordid');
    }

}
