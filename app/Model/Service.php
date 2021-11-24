<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Service extends Model implements ContractsAuditable
{
    use Auditable;
    protected $primaryKey = 'service_recordid';

    // protected $auditEvents = [
    //     'updated',
    //     'deleted',
    //     'created',
    // ];

    protected $fillable = [
        'service_recordid', 'service_name', 'service_alternate_name', 'service_organization', 'service_description', 'service_locations', 'service_url', 'service_email', 'service_status', 'service_taxonomy', 'service_application_process', 'service_wait_time', 'service_fees', 'service_accreditations', 'service_licenses', 'service_phones', 'service_schedule', 'service_contacts', 'service_details', 'service_address', 'service_metadata', 'flag', 'service_program', 'service_airs_taxonomy_x', 'service_code', 'access_requirement', 'SDOH_code', 'code_category_ids','procedure_grouping'
    ];

    public function organizations()
    {
        $this->primaryKey = 'service_recordid';
        return $this->belongsTo('App\Model\Organization', 'service_organization', 'organization_recordid');
    }
    public function getOrganizations()
    {
        $this->primaryKey = 'service_recordid';
        return $this->belongsToMany('App\Model\Organization', 'service_organizations', 'service_recordid', 'organization_recordid');
    }

    public function locations()
    {
        $this->primaryKey = 'service_recordid';

        return $this->belongsToMany('App\Model\Location', 'service_locations', 'service_recordid', 'location_recordid');
    }

    public function details()
    {

        return $this->belongsToMany('App\Model\Detail', 'service_details', 'service_recordid', 'detail_recordid');
    }

    public function taxonomy()
    {
        return $this->belongsToMany('App\Model\Taxonomy', 'service_taxonomies', 'service_recordid', 'taxonomy_recordid');
    }

    public function phone()
    {

        return $this->belongsToMany('App\Model\Phone', 'service_phones', 'service_recordid', 'phone_recordid');
    }

    public function schedules()
    {
        $this->primaryKey = 'service_recordid';

        return $this->belongsToMany('App\Model\Schedule', 'service_schedules', 'service_recordid', 'schedule_recordid');
    }

    public function contact()
    {
        return $this->belongsToMany('App\Model\Contact', 'service_contacts', 'service_recordid', 'contact_recordid');
    }


    public function languages()
    {
        return $this->hasMany('App\Model\Language', 'language_service', 'service_recordid');
    }

    public function address()
    {
        $this->primaryKey = 'service_recordid';

        return $this->belongsToMany('App\Model\Address', 'service_addresses', 'service_recordid', 'address_recordid');
    }
    public function program()
    {
        $this->primaryKey = 'service_recordid';

        return $this->belongsToMany('App\Model\Program', 'service_programs', 'service_recordid', 'program_recordid');
    }
    // public function codes()
    // {
    //     $this->primaryKey = 'service_recordid';

    //     return $this->belongsToMany('App\Model\Code', 'service_codes', 'service_id', 'code_id');
    // }
    /**
     * Get all of the comments for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codes(): HasMany
    {
        return $this->hasMany(CodeLedger::class, 'service_recordid', 'service_recordid');
    }

    /**
     * The service area that belong to the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(ServiceArea::class, 'area_services', 'service_recordid', 'service_area_id');
    }
    /**
     * The fees that belong to the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fees(): BelongsToMany
    {
        return $this->belongsToMany(FeeOption::class, 'service_fees', 'service_recordid', 'fees_id');
    }
}
