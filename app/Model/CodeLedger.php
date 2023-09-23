<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeLedger extends Model
{
    use SoftDeletes;
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
    /**
     * Get the code that owns the CodeLedger
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function code_data(): BelongsTo
    {
        return $this->belongsTo(Code::class, 'SDOH_code', 'id');
    }
    /**
     * Get the get_code_system that owns the Code
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_code_system(): BelongsTo
    {
        return $this->belongsTo(CodeSystem::class, 'code_type', 'id');
    }
}
