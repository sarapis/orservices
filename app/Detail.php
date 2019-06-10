<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Detail extends Model
{
	use Sortable;

    protected $table = 'details';

    protected $primaryKey = 'detail_recordid';
    
	public $timestamps = false;

	public function organization()
    {
        return $this->belongsTo('App\Organization', 'detail_organizations', 'organization_recordid');
    }

    public function location()
    {
        return $this->hasMany('App\Location', 'location_recordid', 'detail_locations');
    }
}
