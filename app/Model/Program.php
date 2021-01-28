<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $primaryKey = 'program_recordid';
    protected $fillable = [
        'program_recordid', 'alternate_name', 'organizations', 'services', 'name'
    ];

    public function service()
    {
        $this->primaryKey = 'program_recordid';

        return $this->belongsToMany('App\Model\Service', 'service_programs', 'program_recordid', 'service_recordid');
    }
    public function organization()
    {
        $this->primaryKey = 'program_recordid';

        return $this->belongsToMany('App\Model\Organization', 'organization_programs', 'program_recordid', 'organization_recordid');
    }
}
