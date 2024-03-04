<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;

class Taxonomy extends Model
{
    protected $primaryKey = 'taxonomy_recordid';

    protected $fillable = [
        'taxonomy_recordid', 'taxonomy_name', 'taxonomy_parent_name', 'taxonomy_grandparent_name', 'taxonomy_vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'taxonomy_parent_recordid', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag', 'category_logo', 'category_logo_white', 'exclude_vocabulary', 'badge_color', 'order', 'taxonomy', 'x_taxonomies', 'created_by', 'status', 'temp_service_recordid', 'temp_organization_recordid', 'added_term', 'code', 'term_uri', 'taxonomy_tag', 'updated_by'
    ];

    public function childs()
    {
        return $this->hasMany('App\Model\Taxonomy', 'taxonomy_parent_name', 'taxonomy_recordid');
    }

    public function parent()
    {
        return $this->belongsTo('App\Model\Taxonomy', 'taxonomy_parent_name', 'taxonomy_recordid');
    }

    public function service()
    {
        return $this->belongsToMany('App\Model\Service', 'service_taxonomies', 'taxonomy_recordid', 'service_recordid');
    }

    public function taxonomy_type()
    {
        return $this->belongsToMany('App\Model\TaxonomyType', 'taxonomy_terms', 'taxonomy_recordid', 'taxonomy_type_recordid');
    }

    public function additional_taxonomy_type()
    {
        return $this->belongsToMany('App\Model\TaxonomyType', 'additional_taxonomies', 'taxonomy_recordid', 'taxonomy_type_recordid');
    }

    public function alt_taxonomies()
    {
        return $this->belongsToMany('App\Model\Alt_taxonomy', 'alt_taxonomies_term_relation');
    }

    /**
     * Get the user that owns the Taxonomy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Get the service that owns the Taxonomy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function temp_service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'temp_service_recordid', 'service_recordid');
    }

    /**
     * Get the organization that owns the Taxonomy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function temp_organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'temp_organization_recordid', 'organization_recordid');
    }

    /**
     * @return array
     */
    public static function getTaxonomyRecordids(): array
    {
        $count_metas = MetaFilter::count();
        $metas = MetaFilter::all();
        $layout = Layout::find(1);

        $taxonomy_serviceids = [];
        $filter_label = Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label;
        if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label' && $layout->hide_service_category_with_no_filter_service == 1) {
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
        if (count($taxonomy_serviceids) > 0) {
            return ServiceTaxonomy::whereIn('service_recordid', $taxonomy_serviceids)->pluck('taxonomy_recordid')->unique()->toArray();
        }
        return [];
    }
}
