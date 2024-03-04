<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cost_recordid', 'services', 'valid_from', 'valid_to', 'option', 'currency', 'amount', 'amount_description', 'attributes', 'created_by'
    ];

    public function services()
    {
        $this->primaryKey = 'cost_recordid';
        return $this->belongsToMany('App\Model\Service', 'service_costs', 'cost_recordid', 'service_recordid');
    }
}
