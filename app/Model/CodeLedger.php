<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodeLedger extends Model
{
    protected $fillable = [
        'rating', 'service_recordid', 'organization_recordid', 'SDOH_code', 'resource', 'description', 'code_type', 'code', 'created_by'
    ];
    /**
     * Get the user that owns the CodeLedger
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_recordid', 'service_recordid');
    }
    /**
     * Get the user that owns the CodeLedger
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_recordid', 'organization_recordid');
    }
}
