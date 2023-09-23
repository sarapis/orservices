<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail extends Model
{
    protected $primaryKey = 'detail_recordid';

    protected $fillable = [
        'detail_recordid', 'detail_value', 'detail_type', 'detail_description', 'detail_organizations', 'detail_services', 'detail_locations', 'flag', 'phones', 'contacts', 'notes', 'language', 'parent'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'detail_organizations', 'organization_recordid');
    }
    public function languageData()
    {
        return $this->belongsTo('App\Model\Language', 'language', 'id');
    }

    public function location()
    {
        return $this->hasMany('App\Model\Location', 'location_recordid', 'detail_locations');
    }
    /**
     * Get the requireDocument that owns the Detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requireDocument(): BelongsTo
    {
        return $this->belongsTo(RequiredDocument::class, 'id', 'detail_id');
    }
    public function getRequireDocumentNameAttribute()
    {
        if ($this->requireDocument && $this->requireDocument->document_link) {
            return '<a href="' . $this->requireDocument->document_link . '">' . ($this->requireDocument && $this->requireDocument->document_title ? $this->requireDocument->document_title : $this->detail_type) . '</a>';
        } else if ($this->requireDocument && $this->requireDocument->document_title) {
            return $this->requireDocument->document_title;
        } else {
            return $this->detail_type;
        }
    }
}
