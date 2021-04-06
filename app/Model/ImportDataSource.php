<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportDataSource extends Model
{
    protected $fillable = [
        'name', 'format', 'airtable_api_key', 'airtable_base_id', 'mode', 'auto_sync', 'sync_hours', 'last_imports', 'organization_tags', 'created_by', 'updated_by', 'import_type', 'source_file'
    ];

    /**
     * Get the user that owns the ImportDataSource
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airtableKeyInfo(): BelongsTo
    {
        return $this->belongsTo(Airtablekeyinfo::class, 'airtable_api_key', 'id');
    }
}
