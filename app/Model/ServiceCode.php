<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCode extends Model
{
    protected $fillable = [
        'service_id', 'code_id'
    ];
    /**
     * Get the user that owns the ServiceCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function code(): BelongsTo
    {
        return $this->belongsTo(Code::class, 'code_id', 'id');
    }
    /**
     * Get the user that owns the ServiceCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(service::class, 'service_id', 'service_recordid');
    }
}
