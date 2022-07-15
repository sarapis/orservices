<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SessionData extends Model
{
    protected $fillable = [
        'session_recordid', 'session_name', 'session_organization', 'session_method', 'session_disposition', 'session_records_edited', 'session_notes', 'session_status', 'session_verification_status', 'session_edits', 'session_performed_by', 'session_performed_at', 'session_verify', 'session_start', 'session_end', 'session_duration', 'session_start_datetime', 'session_end_datetime', 'organization_services', 'organization_status', 'session_service', 'service_status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'session_performed_by', 'id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'session_organization', 'organization_recordid');
    }
    public function service()
    {
        return $this->belongsTo('App\Model\Service', 'session_service', 'service_recordid');
    }
    public function getInteraction()
    {
        return $this->belongsTo('App\Model\SessionInteraction', 'session_recordid', 'interaction_session');
    }
    public function getOrganizationStatus()
    {
        return $this->belongsTo('App\Model\OrganizationStatus', 'organization_status', 'id');
    }
    public function getServiceStatus()
    {
        return $this->belongsTo('App\Model\ServiceStatus', 'service_status', 'id');
    }
    public function get_disposition_data()
    {
        return $this->belongsTo('App\Model\Disposition', 'session_disposition', 'id');
    }
    public function get_interaction_method_data()
    {
        return $this->belongsTo('App\Model\InteractionMethod', 'session_method', 'id');
    }
    // public function getInteraction()
    // {
    //     return $this->hasMany('App\Model\SessionInteraction', 'interaction_session', 'session_recordid');
    // }
}
