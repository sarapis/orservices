<?php

namespace App\Providers;

use App\Model\Alt_taxonomy;
use App\Model\Layout;
use App\Model\Map;
use App\Model\Organization;
use App\Model\Page;
use App\Model\Source_data;
use App\Model\Taxonomy;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.sidebar', function ($view) {
            $parent_taxonomies = Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_parent_name')->pluck('taxonomy_parent_name')->toArray();
            $son_taxonomies = Taxonomy::pluck('taxonomy_name')->toArray();
            $target_taxonomies = Taxonomy::where('taxonomy_parent_name', 'Target Populations')->orderBy('taxonomy_name', 'asc')->get();
            $grandparent_taxonomies = Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_grandparent_name')->pluck('taxonomy_grandparent_name')->toArray();

            $view->with(compact('parent_taxonomies', 'son_taxonomies', 'target_taxonomies', 'grandparent_taxonomies'));
        });
        view()->composer('layouts.script', function ($view) {
            $map = Map::find(1);
            $view->with('map', $map);
        });

        view()->composer('layouts.analytics', function ($view) {
            $analytics = Page::find(4);
            $view->with('analytics', $analytics);
        });

        view()->composer('layouts.header', function ($view) {
            $view->with('layout', Layout::first());
        });
        view()->composer('layouts.style', function ($view) {
            $view->with('layout', Layout::first());
        });

        view()->composer('layouts.footer', function ($view) {
            $view->with('layout', Layout::first());
        });

        view()->composer('backLayout.sidebarMenu', function ($view) {
            $source_data = Source_data::find(1);
            $layout = Layout::first();
            $view->with(compact('source_data', 'layout'));
        });

        // view()->composer('backLayout.sidebarMenu', function ($view) {
        //     $source_data = Source_data::find(1);
        //     $view->with('source_data', $source_data);
        // });

        view()->composer('layouts.filter', function ($view) {
            $view->with('layout', Layout::first());
        });

        view()->composer('layouts.filter_organization', function ($view) {
            $grandparent_taxonomies = Alt_taxonomy::all();

            $taxonomy_tree = [];
            if (count($grandparent_taxonomies) > 0) {
                foreach ($grandparent_taxonomies as $key => $grandparent) {
                    $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
                    $terms = $grandparent->terms()->get();
                    $taxonomy_parent_name_list = [];
                    foreach ($terms as $term_key => $term) {
                        array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
                    }

                    $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

                    $parent_taxonomy = [];
                    $grandparent_service_count = 0;
                    foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                        $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                        $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                        if ($parent_count == $term_count) {
                            $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                            $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                            $child_data['child_taxonomies'] = $child_taxonomies;
                            array_push($parent_taxonomy, $child_data);
                        } else {
                            foreach ($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                                $child_data['parent_taxonomy'] = $child_term;
                                $child_data['child_taxonomies'] = "";
                                array_push($parent_taxonomy, $child_data);
                            }
                        }
                    }
                    $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                    array_push($taxonomy_tree, $taxonomy_data);
                }
            } else {
                $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
                // $parent_taxonomy_data = [];
                // foreach($parent_taxonomies as $parent_taxonomy) {
                //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
                //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
                //     array_push($parent_taxonomy_data, $child_data);
                // }
                $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
            }

            $organization_tags = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();

            $tag_list = [];
            foreach ($organization_tags as $key => $value) {
                $tags = explode(",", trim($value->organization_tag));
                $tag_list = array_merge($tag_list, $tags);
            }
            $tag_list = array_unique($tag_list);

            $organization_tagsArray = [];
            foreach ($tag_list as $key => $value) {
                $organization_tagsArray[$value] = $value;
            }

            // $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            $layout = Layout::first();
            $view->with(compact('layout', 'taxonomy_tree', 'organization_tagsArray'));
        });
    }
}
