<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fees', 'services', 'created_by', 'updated_by'
    ];

    // /**
    //  * Get the service that owns the ServiceArea
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function service(): BelongsTo
    // {
    //     return $this->belongsTo(Service::class, 'services', 'service_recordid');
    // }

    /**
     * The services that belong to the FeeOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services_fees(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_fees', 'fees_id', 'service_recordid');
    }
}
