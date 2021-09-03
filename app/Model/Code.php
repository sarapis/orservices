<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'code_system', 'resource', 'resource_element', 'category', 'description', 'is_panel_code', 'is_multiselect', 'created_by', 'updated_by'
    ];
    // /**
    //  * Get all of the comments for the Code
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function services(): HasMany
    // {
    //     return $this->hasMany(Service::class, 'service_code', 'code');
    // }
    // /**
    //  * The roles that belong to the Code
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function services(): BelongsToMany
    // {
    //     return $this->belongsToMany(Service::class, 'service_codes', 'code_id', 'service_id');
    // }
    /**
     * Get the user that owns the Code
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function code_ledger(): BelongsTo
    {
        return $this->belongsTo(CodeLedger::class, 'id', 'SDOH_code');
    }
}
