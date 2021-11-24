<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'auto_sync', 'configuration_id', 'status'
    ];

    /**
     * Get the export configuration that owns the ExportHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function configuration(): BelongsTo
    {
        return $this->belongsTo(ExportConfiguration::class,'configuration_id');
    }
}
