<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Organization extends Model implements ContractsAuditable
{
    protected $primaryKey = 'organization_recordid';

    use Auditable;

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'latest_updated_date',
    ];

    protected $auditEvents = [
        'updated',
        'deleted',
        'created',
    ];
    // protected $timestamps = false;

    protected $fillable = [
        'organization_recordid', 'organization_name', 'organization_alternate_name', 'organization_logo_x', 'organization_x_uid', 'organization_description', 'organization_email', 'organization_forms_x_filename', 'organization_forms_x_url', 'organization_url', 'organization_status_x', 'organization_status_sort', 'organization_legal_status', 'organization_tax_status', 'organization_tax_id', 'organization_year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_tag', 'organization_airs_taxonomy_x', 'flag', 'organization_website_rating', 'organization_code', 'facebook_url', 'twitter_url', 'instagram_url', 'last_verified_at', 'bookmark', 'last_verified_by', 'created_by', 'updated_by', 'latest_updated_date', 'parent_organization', 'logo', 'funding'
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

    public function fundings()
    {
        $this->primaryKey = 'organization_recordid';
        return $this->belongsToMany('App\Model\Funding', 'organization_fundings', 'organization_recordid', 'funding_recordid');
    }

    public function identifiers()
    {
        $this->primaryKey = 'organization_recordid';
        return $this->belongsToMany('App\Model\Identifier', 'organization_identifiers', 'organization_recordid', 'identifier_recordid');
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
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Model\Contact', 'organization_contacts', 'organization_recordid', 'contact_recordid');
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

    /**
     * Get the get_updated_by that owns the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function get_updated_date($row)
    {
        $updated_date_Array = [];
        $final_date = '';
        $contact_updated = $row->contact->pluck('updated_at')->toArray();
        $location_updated = $row->location->pluck('updated_at')->toArray();
        $phones_updated = $row->phones->pluck('updated_at')->toArray();
        $service_updated = $row->getServices->pluck('updated_at')->toArray();
        $organization_updated = [$row->updated_at];
        $updated_date_Array = array_merge($contact_updated, $location_updated, $phones_updated, $service_updated, $organization_updated);
        foreach ($updated_date_Array as $key => $value) {
            $updated_date_Array[0] = Carbon::parse($updated_date_Array[0]);
            $updated_date_Array[1] = Carbon::parse($updated_date_Array[1]);
            $value = Carbon::parse($value);
            if (empty($final_date) && isset($updated_date_Array[0]) && isset($updated_date_Array[1])) {
                if ($updated_date_Array[0]->gt($updated_date_Array[1])) {
                    $final_date = $updated_date_Array[0];
                } else {
                    $final_date = $updated_date_Array[1];
                }
            }
            if (!empty($final_date) && $key > 1) {
                if ($final_date->gt($value)) {
                    $final_date = $final_date;
                } else {
                    $final_date = $value;
                }
            }
        }
        return (!empty($final_date) ? date('Y-m-d H:i:s', strtotime($final_date)) : date('Y-m-d H:i:s', strtotime($row->updated_at)));
    }

    public function get_latest_updated($row, $search_by)
    {
        $updated_date_Array = collect();
        $final_date = '';
        $final_value = '';
        $contact_updated = $row->contact;
        $updated_date_Array = $updated_date_Array->merge($contact_updated);
        $location_updated = $row->location;
        $updated_date_Array = $updated_date_Array->merge($location_updated);
        $phones_updated = $row->phones;
        $updated_date_Array = $updated_date_Array->merge($phones_updated);
        $service_recordids = [];
        $service_recordids = array_merge($service_recordids, $row->services()->pluck('service_recordid')->toArray(), $row->getServices->pluck('service_recordid')->toArray());
        $service_recordids = count($service_recordids) > 0 ? array_values(array_unique($service_recordids)) : [];
        $services = Service::whereIn('service_recordid', $service_recordids)->get();
        $updated_date_Array = $updated_date_Array->merge($services);
        $organization = $this->where('organization_recordid', $row->organization_recordid)->get();
        $updated_date_Array = $updated_date_Array->merge($organization);

        foreach ($updated_date_Array as $key => $value) {
            $value->updated_at = $value->updated_at ? Carbon::parse($value->updated_at) : null;

            if (empty($final_value) && isset($updated_date_Array[0]) && isset($updated_date_Array[1])) {
                $updated_date_Array[0]->updated_at = $updated_date_Array[0]->updated_at ? Carbon::parse($updated_date_Array[0]->updated_at) : null;
                $updated_date_Array[1]->updated_at = $updated_date_Array[1]->updated_at ? Carbon::parse($updated_date_Array[1]->updated_at) : null;
                if ($updated_date_Array[0]->updated_at && $updated_date_Array[1]->updated_at && ($updated_date_Array[0]->updated_at)->gt($updated_date_Array[1]->updated_at)) {
                    $final_value = $updated_date_Array[0];
                } else {
                    $final_value = $updated_date_Array[1];
                }
            }
            if (!empty($final_value) && $key > 1) {
                if ($final_value->updated_at && $value->updated_at && ($final_value->updated_at)->gt($value->updated_at)) {
                    $final_value = $final_value;
                } else {
                    $final_value = $value;
                }
            }
        }
        if ($search_by == 'updated_by') {
            if (!empty($final_value) && $final_value->updated_by) {
                $user = User::whereId($final_value->updated_by)->first();
                return $user->first_name . ' ' . $user->last_name;
            } elseif ($row->updated_by) {
                $user = User::whereId($row->updated_by)->first();
                return $user->first_name . ' ' . $user->last_name;
            } else {
                return '';
            }
        } else {
            $organization = Organization::where('organization_recordid', $row->organization_recordid)->first();
            // dd(date('Y-m-d H:i:s', strtotime($final_value->updated_at)), $final_value->updated_at, $final_value);
            if (!empty($final_value)) {
                $organization->timestamps = false;
                $organization->latest_updated_date = date('Y-m-d H:i:s', strtotime($final_value->updated_at));
                $organization->save();
            } else {
                $organization->timestamps = false;
                $organization->latest_updated_date = date('Y-m-d H:i:s', strtotime($organization->updated_at));
                $organization->save();
            }

            $organizations = Organization::whereNull('latest_updated_date')->get();
            foreach ($organizations as $key => $value) {
                $value->timestamps = false;
                $value->latest_updated_date = date('Y-m-d H:i:s', strtotime($value->updated_at));
                $value->save();
            }
            return (!empty($organization) && $organization->latest_updated_date ? date('Y-m-d H:i:s', strtotime($organization->latest_updated_date)) : (!empty($final_value) ? date('Y-m-d H:i:s', strtotime($final_value->updated_at)) : date('Y-m-d H:i:s', strtotime($row->updated_at))));
        }
    }

    /**
     * Get the parent that owns the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_organization', 'organization_recordid');
    }

    public function getOrganizationPhonesDataAttribute()
    {
        $phone_number_info = '';
        $mainPhoneNumber = [];
        $phone_number_info_array = [];

        if (isset($this->phones) && count($this->phones) > 0) {
            foreach ($this->phones as $valueV) {
                // $valueV->main_priority == '1' &&
                $valueV->phone_number = preg_replace('/[^0-9]/', '', $valueV->phone_number);

                $phone = $valueV->phone_number;

                $ac = substr($phone, 0, 3);
                $prefix = substr($phone, 3, 3);
                $suffix = substr($phone, 6);

                $valueV->phone_number = '(' . $ac . ') ' . $prefix . ' - ' . $suffix;
                if (count($mainPhoneNumber) == 0) {
                    if ($valueV->phone_language) {
                        $languageId = $valueV->phone_language ? explode(',', $valueV->phone_language) : [];
                        $languages = \App\Model\Language::whereIn('language_recordid', $languageId)
                            ->pluck('language')->unique()
                            ->toArray();
                        $valueV->phone_language = implode(', ', $languages);
                    }
                    $mainPhoneNumber[] = $valueV;
                }
            }
        }
        $mainPhoneNumber = array_merge($mainPhoneNumber, $phone_number_info_array);
        $phoneData = '';
        foreach ($mainPhoneNumber as $key => $item) {
            $phoneData .= '<a href="tel:' . $item->phone_number . '">' . $item->phone_number . '</a>&nbsp;&nbsp;' . ($item->phone_extension ? 'ext. ' . $item->phone_extension : '') . '&nbsp' . ($item->type ? '(' . $item->type->type . ')' : '');
            if ($item->phone_language)
                $phoneData .= '<br>' . $item->phone_language;
            $phoneData .= $item->phone_description ? '- ' . $item->phone_description : '';
        }
        return $phoneData;
    }

    /**
     * @param array $values
     * @param string $operations
     * @return mixed
     */
    public static function getOrganizationStatusMeta(array $values = [], string $operations = '')
    {
        return Organization::where(function ($query) use ($values, $operations) {
            foreach ($values as $keyword) {
                if ($operations == 'Include') {
                    $query = $query->orWhere('organization_status_x', 'LIKE', "%$keyword%");
                }
                if ($operations == 'Exclude') {
                    $query = $query->orWhere('organization_status_x', 'NOT LIKE', "%$keyword%");
                }
            }
            return $query;
        })->pluck('organization_recordid')->toArray();
    }

    /**
     * @param array $values
     * @param string $operations
     * @return mixed
     */
    public static function getOrganizationTagMeta(array $values = [], string $operations = '')
    {
        return Organization::where(function ($query) use ($values, $operations) {
            foreach ($values as $keyword) {
                if ($keyword && $operations == 'Include') {
                    $query = $query->orWhereRaw('find_in_set(' . $keyword . ', organization_tag)');
                }
                if ($keyword && $operations == 'Exclude') {
                    $query = $query->orWhereRaw('NOT find_in_set(' . $keyword . ', organization_tag)');
                }
            }
            return $query;
        })->pluck('organization_recordid')->toArray();
    }

    public function getOrganizationServiceCountAttribute($meta_status = '')
    {
        return $this->getFilterService($meta_status)->count();
    }

    public function getOrganizationServiceDataAttribute()
    {
        return $this->getFilterService();
    }

    public function getFilterService($meta_status = '')
    {
        $count_metas = MetaFilter::count();
        $metas = MetaFilter::all();
        $layout = Layout::find(1);

        if ($this->services()->count() == 0) {
            $services = $this->getServices();
        } else {
            $services = $this->services();
        }
        $taxonomy_serviceids = [];
        $filter_label = Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label;
        if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label' && $layout->hide_organizations_with_no_filtered_services == 1) {
            foreach ($metas as $key => $meta) {
                $values = explode(",", $meta->values);
                if ($meta->facet == 'Service_status') {
                    $serviceStatusIds = Service::getServiceStatusMeta($values, $meta->operations);
                    $taxonomy_serviceids = array_merge($serviceStatusIds, $taxonomy_serviceids);
                }
                if ($meta->facet == 'service_tag') {
                    $service_tag_ids = Service::getServiceTagMeta($values, $meta->operations);
                    $taxonomy_serviceids = array_merge($service_tag_ids, $taxonomy_serviceids);
                }
                if ($meta->facet == 'organization_status') {
                    $organizations_status_ids = Organization::getOrganizationStatusMeta($values, $meta->operations);
                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_status_ids)->pluck('service_recordid')->toArray();
                    $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                }
                if ($meta->facet == 'organization_tag') {
                    $organizations_tags_ids = Organization::getOrganizationTagMeta($values, $meta->operations);
                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_tags_ids)->pluck('service_recordid')->toArray();
                    $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                }
            }
        }
        if (count($taxonomy_serviceids) > 0 && $services->count() > 0) {
            $services->whereIn('service_recordid', $taxonomy_serviceids);
        }
        return $services;
    }
}
