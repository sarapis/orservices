<?php

namespace App\Http\Controllers\backend;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\Organization;
use App\Model\Service;
use App\Model\Taxonomy;
use App\Model\TaxonomyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
        $serviceEligibilityId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Eligibility')->first();

        $service_category_types = Taxonomy::orderBy('taxonomy_name')->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::orderBy('taxonomy_name')->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');

        return view('backEnd.tables.tb_services', compact('conditions', 'goals', 'activities', 'organizations', 'service_category_types', 'service_eligibility_types'));
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
            ->rawColumns(['codes', 'service_category', 'service_eligibility', 'procedure_grouping'])
            ->make(true);
    }
}
