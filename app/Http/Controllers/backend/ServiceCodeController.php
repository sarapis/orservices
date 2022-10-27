<?php

namespace App\Http\Controllers\backend;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\Organization;
use App\Model\OrganizationTag;
use App\Model\Service;
use App\Model\ServiceStatus;
use App\Model\ServiceTag;
use App\Model\Taxonomy;
use App\Model\TaxonomyType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ServiceCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conditions = Code::where('resource', 'Condition')->pluck('description', 'id');
        $goals = Code::where('resource', 'Goal')->pluck('description', 'id');
        $activities = Code::where('resource', 'Procedure')->pluck('description', 'id');
        $organizations = Organization::pluck('organization_name', 'organization_recordid');
        $service_statuses = ServiceStatus::pluck('status', 'id');
        $service_tags = ServiceTag::pluck('tag', 'id');
        $organization_tags = OrganizationTag::pluck('tag', 'id');

        $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
        $serviceEligibilityId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Eligibility')->first();

        $service_category_types_list = $serviceCategoryId ?  Taxonomy::orderBy('taxonomy_name')->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->whereNull('taxonomy_parent_name')->get() : [];

        $service_category_types = ['no_category' => 'No Category'];
        foreach ($service_category_types_list as $value) {
            $service_category_types[$value->taxonomy_recordid] = $value->taxonomy_name;
            $taxonomy_child_list = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value->taxonomy_recordid . '%')->get();
            if ($taxonomy_child_list) {
                foreach ($taxonomy_child_list as $value1) {
                    $service_category_types[$value1->taxonomy_recordid] = '- '  . $value1->taxonomy_name;
                    $taxonomy_child_list1 = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->get();
                    if ($taxonomy_child_list1) {
                        foreach ($taxonomy_child_list1 as $value2) {
                            $service_category_types[$value2->taxonomy_recordid] = '-- '  . $value2->taxonomy_name;
                            $taxonomy_child_list2 = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value2->taxonomy_recordid . '%')->get();
                            if ($taxonomy_child_list2) {
                                foreach ($taxonomy_child_list2 as $value3) {
                                    $service_category_types[$value3->taxonomy_recordid] = '--- '  . $value3->taxonomy_name;
                                }
                            }
                        }
                    }
                }
            }
        }

        // $service_eligibility_types = $serviceEligibilityId ? Taxonomy::orderBy('taxonomy_name')->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid') : [];
        $service_eligibility_types_list = $serviceEligibilityId ? Taxonomy::orderBy('taxonomy_name')->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->whereNull('taxonomy_parent_name')->get() : [];

        $service_eligibility_types = ['no_eligibility' => 'No Eligibility'];
        foreach ($service_eligibility_types_list as $value) {
            $service_eligibility_types[$value->taxonomy_recordid] = $value->taxonomy_name;
            $taxonomy_child_list = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value->taxonomy_recordid . '%')->get();
            if ($taxonomy_child_list) {
                foreach ($taxonomy_child_list as $value1) {
                    $service_eligibility_types[$value1->taxonomy_recordid] = '- '  . $value1->taxonomy_name;
                    $taxonomy_child_list1 = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->get();
                    if ($taxonomy_child_list1) {
                        foreach ($taxonomy_child_list1 as $value2) {
                            $service_eligibility_types[$value2->taxonomy_recordid] = '-- '  . $value2->taxonomy_name;
                            $taxonomy_child_list2 = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value2->taxonomy_recordid . '%')->get();
                            if ($taxonomy_child_list2) {
                                foreach ($taxonomy_child_list2 as $value3) {
                                    $service_eligibility_types[$value3->taxonomy_recordid] = '--- '  . $value3->taxonomy_name;
                                }
                            }
                        }
                    }
                }
            }
        }




        return view('backEnd.tables.tb_services', compact('conditions', 'goals', 'activities', 'organizations', 'service_category_types', 'service_eligibility_types', 'service_statuses', 'service_tags', 'organization_tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function tb_services_export(Request $request)
    {
        try {
            // return Excel::download(new CodeExport, 'codes.csv');
            Excel::store(new ServiceExport($request), 'services.csv', 'csv');
            return response()->json([
                'path' => url('/csv/services.csv'),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in service code export : ' . $th);
            return response()->json([
                'path' => '',
                'success' => false
            ], 500);
        }
    }
    public function get_service_data(Request $request)
    {
        $services = Service::with('organizations', 'codes')->orderBy('service_recordid', 'asc');
        return DataTables::of($services)
            ->addColumn('codes', function ($row) {
                $links = '';
                $service_grouping = $row->procedure_grouping ? unserialize($row->procedure_grouping) : [];
                $temp_array = [];
                if ($row->codes && count($row->codes) > 0) {
                    $codes = $row->codes;
                    foreach ($codes as $key => $value) {
                        // $procedure_grouping = '';
                        $code_data = $value->code_data;
                        // $links .= $value->resource . ' - ' . $value->description . ' - ' . $value->rating;
                        $temp_array[] = $code_data->code_id . ' - ' . $value->rating;
                        // if (is_array($service_grouping) && count($service_grouping) > 0) {
                        //     $category = str_replace(' ', '_', str_replace('/', '_', str_replace(',', '_', $value->code_data->category)));
                        //     if ($category) {
                        //         $results = array_filter($service_grouping, function ($value) use ($category) {
                        //             return strpos($value, $category) !== false;
                        //         });
                        //         $results = array_values($results);
                        //         $procedure_grouping .= ' - ';
                        //         foreach ($results as $key1 => $value1) {
                        //             if (strpos($value1, $category) !== false) {
                        //                 $value1 = str_replace('_', ' ', str_replace($category . '|', '', $value1));
                        //                 $procedure_grouping .= ($key1 == 0 ? $value1 : ', ' . $value1);
                        //             }
                        //         }
                        //         // $procedure_grouping .= '<br>';
                        //     }
                        // }
                        // $links .= $procedure_grouping . '<br>';
                    }
                }
                return count($temp_array) > 0 ? implode(',', $temp_array) : '';
            })
            ->addColumn('service_category', function ($row) {
                $link = '';
                if (isset($row->taxonomy) && count($row->taxonomy) > 0) {
                    foreach ($row->taxonomy as $service_taxonomy_info) {
                        if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 && $service_taxonomy_info->taxonomy_type[0]->name == 'Service Category') {
                            if ($row->service_taxonomy != null) {
                                $link .= '<a class="panel-link" style="background-color: ' . ($service_taxonomy_info->badge_color ? "#" . $service_taxonomy_info->badge_color : "#000") . ' !important; color:#fff !important;">' . $service_taxonomy_info->taxonomy_name . '</a>';
                            }
                        }
                    }
                }
                return $link;
            })
            // ->addColumn('procedure_grouping', function ($row) {
            //     $service_grouping = $row->procedure_grouping ? unserialize($row->procedure_grouping) : [];

            //     $procedure_grouping = '';
            //     if (is_array($service_grouping) && count($service_grouping) > 0) {
            //         $category = $row->code_data ?  $row->code_data->category : '';
            //         $codes = $row->codes;
            //         foreach ($codes as $key => $value1) {
            //             $category = str_replace(' ', '_', str_replace('/', '_', str_replace(',', '_', $value1->code_data->category)));
            //             if ($category) {
            //                 $results = array_filter($service_grouping, function ($value) use ($category) {
            //                     return strpos($value, $category) !== false;
            //                 });
            //                 $results = array_values($results);
            //                 foreach ($results as $key => $value) {
            //                     if (strpos($value, $category) !== false) {
            //                         $value = str_replace('_', ' ', str_replace($category . '|', '', $value));
            //                         $procedure_grouping .= ($key == 0 ? $value : ', ' . $value);
            //                     }
            //                 }
            //                 $procedure_grouping .= '<br>';
            //             }
            //         }
            //     }
            //     return $procedure_grouping;
            // })
            ->addColumn('service_eligibility', function ($row) {
                $link = '';
                if (isset($row->taxonomy) && count($row->taxonomy) > 0) {
                    foreach ($row->taxonomy as $service_taxonomy_info) {
                        if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 && $service_taxonomy_info->taxonomy_type[0]->name == 'Service Eligibility') {
                            if ($row->service_taxonomy != null) {
                                $link .= '<a class="panel-link" style="background-color: ' . ($service_taxonomy_info->badge_color ? "#" . $service_taxonomy_info->badge_color : "#000") . ' !important; color:#fff !important;">' . $service_taxonomy_info->taxonomy_name . '</a>';
                            }
                        }
                    }
                }
                return $link;
            })
            ->editColumn('service_organization', function ($row) {

                $organizations = $row->organizations;
                return $organizations ? $organizations->organization_name : '';
                // if($organizations)
            })
            ->addColumn('organization_tag', function ($row) {

                $organizations = $row->organizations;
                $organization_tag = [];
                if ($organizations && $organizations->organization_tag) {
                    $organization_tags = explode(',', $organizations->organization_tag);
                    foreach ($organization_tags as $key => $value) {
                        $tag = OrganizationTag::whereId($value)->first();
                        if ($tag) {
                            $organization_tag[] = $tag->tag;
                        }
                    }
                }
                return implode(',', $organization_tag);
                // if($organizations)
            })
            ->editColumn('bookmark', function ($row) {
                if ($row->bookmark && $row->bookmark == 1) {
                    return '<a href="javascript:void(0)" class="clickBookmark" data-id="' . $row->id . '" data-value="' . ($row->bookmark ? $row->bookmark : 0) . '"><img src="/images/bookmark.svg"></a>';
                } else {

                    return '<a href="javascript:void(0)" class="clickBookmark" data-id="' . $row->id . '" data-value="0"><img src="/images/unbookmark.svg"></a>';
                }
            })
            ->editColumn('service_name', function ($row) {
                return  ;
            })
            ->editColumn('service_status', function ($row) {
                return $row->get_status ? $row->get_status->status : '';
                // if($organizations)
            })
            ->editColumn('service_tag', function ($row) {
                $tags = [];
                if ($row->service_tag) {
                    $tagsArray = explode(',', $row->service_tag);
                    foreach ($tagsArray as $key => $value) {
                        $service_tag = ServiceTag::whereId($value)->first();
                        if ($service_tag) {
                            $tags[] = '<a href="javascript:void(0)" data-id="' . $row->service_recordid . '" class="serviceTags" data-tags="' .  $row->service_tag . '"> ' . $service_tag->tag . '</a>';;
                        }
                    }
                    return count($tags) > 0 ? implode(',', $tags) : '';
                } else {
                    return '<button type="button" class="btn btn-sm btn-primary serviceTags" data-id="' . $row->service_recordid . '" data-tags="">Add Tag</button>';
                }
                // if($organizations)
            })
            // ->addColumn('services', function ($row) {
            //     $name = '';
            //     if (count($row->services) > 0) {
            //         $services = $row->services()->with('organizations')->get();
            //         $name .= '<ul>';
            //         foreach ($services as $key => $value) {
            //             $name .= '<li>' . $value->service_name . ($value->organizations ? '- ' . $value->organizations->organization_name . ($value->organizations->organization_website_rating ? ' - ' . $value->organizations->organization_website_rating : '') : '') . '</li>';
            //         }
            //         return $name;
            //     }
            // })
            ->filter(function ($query) use ($request) {
                $extraData = $request->get('extraData');

                if ($extraData) {
                    $code_ids = [];
                    $service_ids = [];

                    if (isset($extraData['service_category']) && $extraData['service_category'] != null) {
                        $service_recordids = [];
                        $taxonomies = Taxonomy::whereIn('taxonomy_recordid', $extraData['service_category'])->with('service')->get();
                        foreach ($taxonomies as $key => $value) {
                            if ($value->service && count($value->service) > 0) {
                                foreach ($value->service as $key => $service) {
                                    $service_recordids[] = $service->service_recordid;
                                }
                            }
                        }
                        if (in_array('no_category', $extraData['service_category'])) {
                            $services = Service::cursor();
                            foreach ($services as $key => $service) {
                                if ($service->taxonomy()->count() == 0) {
                                    $service_recordids[] = $service->service_recordid;
                                }
                            }
                        }
                        $query->whereIn('service_recordid', $service_recordids);
                    }
                    if (isset($extraData['service_with_codes']) && $extraData['service_with_codes'] != null && $extraData['service_with_codes'] == "true") {
                        $query->whereNotNull('SDOH_code')->whereNotNull('code_category_ids');
                    }
                    if (isset($extraData['service_eligibility']) && $extraData['service_eligibility'] != null) {
                        $service_recordids = [];
                        $taxonomies = Taxonomy::whereIn('taxonomy_recordid', $extraData['service_eligibility'])->with('service')->get();
                        foreach ($taxonomies as $key => $value) {
                            if ($value->service && count($value->service) > 0) {
                                foreach ($value->service as $key => $service) {
                                    $service_recordids[] = $service->service_recordid;
                                }
                            }
                        }
                        if (in_array('no_eligibility', $extraData['service_eligibility'])) {
                            $services = Service::cursor();
                            foreach ($services as $key => $service) {
                                if ($service->taxonomy()->count() == 0) {
                                    $service_recordids[] = $service->service_recordid;
                                }
                            }
                        }
                        $query->whereIn('service_recordid', $service_recordids);
                    }
                    if (isset($extraData['conditions']) && $extraData['conditions'] != null) {
                        $code_ids = array_merge($code_ids, $extraData['conditions']);
                    }
                    if (isset($extraData['goals']) && $extraData['goals'] != null) {
                        $code_ids = array_merge($code_ids, $extraData['goals']);
                    }
                    if (isset($extraData['activities']) && $extraData['activities'] != null) {
                        $code_ids = array_merge($code_ids, $extraData['activities']);
                    }
                    if (isset($extraData['organizations']) && $extraData['organizations'] != null) {
                        $query->whereIn('service_organization', $extraData['organizations']);
                    }
                    if (isset($extraData['service_status']) && $extraData['service_status'] != null) {
                        $query->where('service_status', $extraData['service_status']);
                    }
                    if (isset($extraData['service_tag']) && $extraData['service_tag'] != null) {
                        $service_tags = count($extraData['service_tag']) > 0 ?  array_filter($extraData['service_tag']) : [];
                        $query = $query->where(function ($q) use ($service_tags) {
                            foreach ($service_tags as $key => $value) {
                                $q->orWhere('service_tag', 'LIKE', '%' . $value . '%');
                            }
                        });
                    }
                    if (isset($extraData['organisation_tag']) && $extraData['organisation_tag'] != null) {
                        $organisation_tags = count($extraData['organisation_tag']) > 0 ?  array_filter($extraData['organisation_tag']) : [];

                        $organization_ids = Organization::where(function ($q) use ($organisation_tags) {
                            foreach ($organisation_tags as $key => $value) {
                                $q->orWhere('organization_tag', 'LIKE', '%' . $value . '%');
                            }
                        })->pluck('organization_recordid')->toArray();
                        $query = $query->whereIn('service_organization', $organization_ids);
                    }
                    if (isset($extraData['service_bookmark_only']) && $extraData['service_bookmark_only'] != null && $extraData['service_bookmark_only'] == "true") {
                        $query = $query->where('bookmark', 1);
                    }
                    if (count($code_ids) > 0) {
                        $codes = Code::whereIn('id', $code_ids)->get();
                        foreach ($codes as $key => $value) {
                            $code_ledger = $value->code_ledger;
                            foreach ($code_ledger as $key => $value) {
                                $service_ids[] = $value->service_recordid;
                            }
                            // $service_ids = array_merge($service_ids, $value->services->pluck('id')->toArray());
                        }
                        $query->whereIn('service_recordid', $service_ids);
                    }
                }
                return $query;
            }, true)
            ->rawColumns(['codes', 'service_category', 'service_eligibility', 'procedure_grouping', 'bookmark', 'service_tag', 'service_name'])
            ->make(true);
    }
    public function saveServiceBookmark(Request $request)
    {
        try {
            $id = $request->id;
            $service = Service::whereId($id)->first();
            $service->bookmark = $request->value;
            $service->updated_at = Carbon::now();
            $service->save();
            // Session::flash('message', 'Service Bookmarked successfully!');
            // Session::flash('status', 'success');
            return response()->json([
                'success' => true,
                'message' => 'Service Bookmarked successfully!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
    public function saveServiceTags(Request $request)
    {
        try {
            $service_recordid = $request->service_recordid;
            $service = Service::where('service_recordid', $service_recordid)->first();
            $service->service_tag = $request->service_tag && is_array($request->service_tag) ? implode(',', $request->service_tag) : '';
            $service->save();
            Session::flash('message', 'Service tag updated successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect()->back();
        }
    }
    public function createNewServiceTag(Request $request)
    {
        try {
            $tag = $request->tag;
            $serviceTag  = ServiceTag::create([
                'tag' => $tag,
                'created_by' => Auth::id()
            ]);
            $service_recordid = $request->service_recordid;
            $service = Service::where('service_recordid', $service_recordid)->first();
            $srvicTag = $service->service_tag != null ? explode(',', $service->service_tag) : [];
            $srvicTag[] = $serviceTag->id;
            if (!empty($srvicTag)) {
                $service->service_tag = implode(',', $srvicTag);
                $service->save();
            }
            Session::flash('message', 'Service tag added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect()->back();
        }
    }
}
