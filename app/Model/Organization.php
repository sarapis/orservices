<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Organization extends Model implements ContractsAuditable
{
    protected $primaryKey = 'organization_recordid';

    use Auditable;
    protected $auditEvents = [
        'updated',
        'deleted',
        'created',
    ];

    protected $fillable = [
        'organization_recordid', 'organization_name', 'organization_alternate_name', 'organization_logo_x', 'organization_x_uid', 'organization_description', 'organization_email', 'organization_forms_x_filename', 'organization_forms_x_url', 'organization_url', 'organization_status_x', 'organization_status_sort', 'organization_legal_status', 'organization_tax_status', 'organization_tax_id', 'organization_year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_tag', 'organization_airs_taxonomy_x', 'flag', 'organization_website_rating', 'organization_code', 'facebook_url', 'twitter_url', 'instagram_url', 'last_verified_at', 'bookmark', 'last_verified_by', 'created_by', 'updated_by'
    ];

    public function services()
    {
        $this->primaryKey = 'organization_recordid';
        return $this->hasMany('App\Model\Service', 'service_organization', 'organization_recordid');
    }
    public function getServices()
    {
        $this->primaryKey = 'organization_recordid';
        return $this->belongsToMany('App\Model\Service', 'service_organizations', 'organization_recordid', 'service_recordid');
    }
    public function phones()
    {
        return $this->belongsToMany('App\Model\Phone', 'organization_phones', 'organization_recordid', 'phone_recordid');
    }

    public function location()
    {
        return $this->hasmany('App\Model\Location', 'location_organization', 'organization_recordid');
    }

    public function contact()
    {
        return $this->hasmany('App\Model\Contact', 'contact_organizations', 'organization_recordid');
        // return $this->belongsToMany('App\Model\Contact', 'organizations_contacts', 'organization_recordid', 'contact_recordid');
    }

    public function details()
    {
        return $this->belongsToMany('App\Model\Detail', 'organization_details', 'organization_recordid', 'detail_recordid');
    }
    public function owner()
    {
        return $this->belongsToMany('App\Model\User', 'organizations_users', 'organization_recordid', 'user_id');
    }
    public function program()
    {
        $this->primaryKey = 'organization_recordid';

        return $this->belongsToMany('App\Model\Program', 'organization_programs', 'organization_recordid', 'program_recordid');
    }
    public function SessionData()
    {
        return $this->hasMany('App\Model\SessionData', 'session_organization', 'organization_recordid');
    }
    /**
     * Get the status_data that owns the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status_data(): BelongsTo
    {
        return $this->belongsTo(OrganizationStatus::class, 'organization_status_x', 'id');
    }
    /**
     * Get the get_last_verified_by that owns the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_last_verified_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_verified_by', 'id');
    }
}
