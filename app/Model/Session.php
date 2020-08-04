<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'session_recordid', 'session_name', 'session_organization', 'session_method', 'session_disposition', 'session_records_edited', 'session_notes', 'session_status', 'session_verification_status', 'session_edits', 'session_performed_by', 'session_performed_at', 'session_verify', 'session_start', 'session_end', 'session_duration', 'session_start_datetime', 'session_end_datetime',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'session_performed_by', 'id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'session_organization', 'organization_recordid');
    }
}
