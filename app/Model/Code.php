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
        'code', 'code_system', 'resource', 'resource_element', 'category', 'description', 'grouping', 'definition', 'is_panel_code', 'is_multiselect', 'created_by', 'updated_by', 'code_id', 'uid', 'notes', 'source'
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
    public function code_ledger(): HasMany
    {
        return $this->hasMany(CodeLedger::class, 'SDOH_code');
    }

    public static function parent($id)
    {
        return SELF::where('code_id', $id)->first();
    }
    /**
     * Get the get_category that owns the Code
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_category(): BelongsTo
    {
        return $this->belongsTo(CodeCategory::class, 'category', 'id');
    }
    /**
     * Get the get_code_system that owns the Code
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_code_system(): BelongsTo
    {
        return $this->belongsTo(CodeSystem::class, 'code_system', 'id');
    }
}
