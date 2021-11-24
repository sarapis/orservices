<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceArea extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'services', 'description', 'created_at', 'updated_at'
    ];

    // /**
    //  * Get the service that owns the ServiceArea
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function services(): BelongsToMany
    // {
    //     return $this->belongsTo(Service::class, 'services', 'service_recordid');
    // }
    /**
     * The services that belong to the ServiceArea
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'area_services', 'service_area_id', 'service_recordid');
    }
}
