<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Language extends Model
{
    use Sortable;

    protected $table = 'languages';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

	public function service()
    {
        return $this->belongsTo('App\Service', 'language_service', 'service_recordid');
    }

    public function location()
    {
        return $this->belongsTo('App\Location', 'language_location', 'location_recordid');
    }

}
