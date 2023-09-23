<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Service extends Model implements ContractsAuditable
{
    use Auditable;

    protected $primaryKey = 'service_recordid';
    public $timestamps = false;

    // protected $auditEvents = [
    //     'updated',
    //     'deleted',
    //     'created',
    // ];

    protected $fillable = [
        'service_recordid', 'service_name', 'service_alternate_name', 'service_organization', 'service_description', 'service_locations', 'service_url', 'service_email', 'service_status', 'service_taxonomy', 'service_application_process', 'service_wait_time', 'service_fees', 'service_accreditations', 'service_licenses', 'service_phones', 'service_schedule', 'service_contacts', 'service_details', 'service_address', 'service_metadata', 'flag', 'service_program', 'service_airs_taxonomy_x', 'service_code', 'access_requirement', 'SDOH_code', 'code_category_ids', 'procedure_grouping', 'service_tag', 'created_by', 'updated_by', 'service_language', 'service_interpretation', 'eligibility_description', 'minimum_age', 'maximum_age', 'service_alert'
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

    public function SessionData()
    {
        return $this->hasMany('App\Model\SessionData', 'session_service', 'service_recordid');
    }

    /**
     * Get the get_status that owns the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_status(): BelongsTo
    {
        return $this->belongsTo(ServiceStatus::class, 'service_status', 'id');
    }

    /**
     * Get all of the require_documents for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function require_documents(): HasMany
    {
        return $this->hasMany(RequiredDocument::class, 'service_recordid', 'service_recordid');
    }

    public function getServiceLanguageDataAttribute()
    {
        $languageIds = $this->service_language ? explode(',', $this->service_language) : [];
        $languges = [];
        foreach ($languageIds as $key => $value) {
            $lan = Language::whereId($value)->first();
            $languges[] = $lan->language;
        }
        return implode(', ', $languges);
    }

    public function getServiceInterpretationDataAttribute()
    {
        $ids = $this->service_interpretation ? explode(',', $this->service_interpretation) : [];
        $interPretations = [];
        foreach ($ids as $key => $value) {
            $data = InterpretationService::whereId($value)->first();
            $interPretations[] = $data->name;
        }
        return implode(', ', $interPretations);
    }

    public function getServiceRequireDocumentDataAttribute(): string
    {
        $require_documents = $this->require_documents;
        $documents = [];
        foreach ($require_documents as $key => $value) {
            if ($value->document_link) {
                $documents[] = '<a href="' . $value->document_link . '" target="_blank">' . ($value->document_title ?? $value->document_type) . '</a>';
            } else {
                $documents[] = $value->document_title ?? $value->document_type;
            }
        }
        return implode(', ', $documents);
    }

    /**
     * @param array $values
     * @param array $operations
     * @return mixed
     */
    public static function getServiceTagMeta(array $values = [], $operations = '')
    {
        return Service::where(function ($query) use ($values, $operations) {
            foreach ($values as $keyword) {
                if ($keyword && $operations == 'Include') {
                    $query = $query->orWhereRaw('find_in_set(' . $keyword . ', service_tag)');
                }
                if ($keyword && $operations == 'Exclude') {
                    $query = $query->orWhereRaw('NOT find_in_set(' . $keyword . ', service_tag)');
                }
            }
            return $query;
        })->pluck('service_recordid')->toArray();
    }

    /**
     * @param array $values
     * @param string $operations
     * @return array
     */
    public static function getServiceStatusMeta(array $values = [], string $operations = '')
    {
        $serviceids = Service::query();

        if ($operations == 'Include') {
            $serviceids->whereIn('service_status', $values);
        }

        if ($operations == 'Exclude') {
            $serviceids->whereNotIn('service_status', $values);
        }
        return $serviceids->pluck('service_recordid')->toArray();
    }

    public function getServicePhonesDataAttribute()
    {
        $mainPhoneNumber = [];
        $phone_number_info_array = [];

        if (isset($this->phone) && count($this->phone) > 0) {
            foreach ($this->phone as $valueV) {
                if ($valueV->main_priority == '1') {
                    $mainPhoneNumber[] = $valueV;
                } else {
                    $phone_number_info_array[] = $valueV;
                }
            }
        }
        $mainPhoneNumber = array_filter(array_merge($mainPhoneNumber, $phone_number_info_array));
        $otherData = '';
        foreach ($mainPhoneNumber as $key => $item) {
            $otherData = '<a href="tel:' . $item->phone_number . '">' . $item->phone_number . '</a>';
            $otherData .= $item->phone_extension ? '&nbsp;&nbsp;ext. ' . $item->phone_extension : '';
            $otherData .=  $item->type ? '&nbsp;(' . $item->type->type . ')' : '';
            $otherData .=  $item->phone_language ? ' ' . $item->get_phone_language($item->id) : '';
        }
        return $otherData;
    }
}
