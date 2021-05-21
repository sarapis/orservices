<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Taxonomy extends Model
{
    protected $primaryKey = 'taxonomy_recordid';

    protected $fillable = [
        'taxonomy_recordid', 'taxonomy_name', 'taxonomy_parent_name', 'taxonomy_grandparent_name', 'taxonomy_vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'taxonomy_parent_recordid', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag', 'category_logo', 'category_logo_white', 'exclude_vocabulary', 'badge_color', 'order', 'taxonomy', 'x_taxonomies', 'created_by', 'status', 'temp_service_recordid', 'temp_organization_recordid', 'added_term'
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
}
