<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionInteraction extends Model
{
    protected $fillable = [
        'interaction_recordid', 'interaction_session', 'interaction_method', 'interaction_disposition', 'interaction_notes', 'interaction_records_edited', 'interaction_timestamp', 'organization_services', 'organization_status'
    ];
    /**
     * Get the disposition that owns the SessionInteraction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disposition(): BelongsTo
    {
        return $this->belongsTo(Disposition::class, 'interaction_disposition', 'id');
    }
}
