<?php

namespace App\Http\Controllers\frontEnd;

use App\Exports\TrackingExport;
use App\Http\Controllers\Controller;
use App\Model\Detail;
use App\Model\Map;
use App\Model\Organization;
use App\Model\OrganizationStatus;
use App\Model\OrganizationTableFilter;
use App\Model\OrganizationTag;
use App\Model\Service;
use App\Model\ServiceTag;
use App\Model\SessionData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\Facades\DataTables;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $map = Map::find(1);
            $organizations = Organization::select('*');
            $organization_tags = OrganizationTag::pluck('tag', 'id');
            $service_tags = ServiceTag::pluck('tag', 'id');
            $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
            $saved_filters = OrganizationTableFilter::where('user_id', Auth::id())->get();
            $users = User::get();

            if (!$request->ajax()) {
                return view('frontEnd.tracking.index', compact('organizations', 'organization_tags', 'organizationStatus', 'service_tags', 'saved_filters', 'map', 'users'));
            }
            return DataTables::of($organizations)
                ->editColumn('services', function ($row) {
                    $service_name = '';
                    if (isset($row->services)) {
                        foreach ($row->services as $key => $service) {
                            $service_name .= '<span class="badge bg-green"> ' . $service->service_name . '</span>';
                        }
                    }
                    return $service_name;
                })
                ->editColumn('phones', function ($row) {
                    $phone_number = '';
                    if (isset($row->phones)) {
                        foreach ($row->phones as $key => $phone) {
                            $phone_number .= '<span class="badge bg-blue"> ' . $phone->phone_number . '</span>';
                        }
                    }
                    return $phone_number;
                })
                ->editColumn('location', function ($row) {
                    $location_name = '';
                    if (isset($row->location)) {
                        foreach ($row->location as $key => $location) {
                            $location_name .= '<span class="badge bg-blue"> ' . $location->location_name . '</span>';
                        }
                    }
                    return $location_name;
                })
                ->editColumn('organization_details', function ($row) {
                    $organization_detail = '';
                    if (isset($row->organization_details)) {
                        foreach ($row->organization_details as $key => $organization_detail) {
                            $organization_detail .= '<span class="badge bg-purple"> ' . $organization_detail->detail_value . '</span>';
                        }
                    }
                    return $organization_detail;
                })
                ->editColumn('contact_name', function ($row) {
                    $location_name = '';
                    if (isset($row->contact()->first()->contact_name)) {
                        $location_name .= '<span class="badge bg-red">' . $row->contact()->first()->contact_name . '</span>';
                    }
                    return $location_name;
                })
                ->editColumn('bookmark', function ($row) {
                    if ($row->bookmark && $row->bookmark == 1) {
                        return '<a href="javascript:void(0)" class="clickBookmark" data-id="' . $row->id . '" data-value="' . ($row->bookmark ? $row->bookmark : 0) . '"><img src="/images/bookmark.svg"></a>';
                    } else {
                        return '<a href="javascript:void(0)" class="clickBookmark" data-id="' . $row->id . '" data-value="0"><img src="/images/unbookmark.svg"></a>';
                    }
                })
                ->editColumn('organization_description', function ($row) {
                    $organization_description = Str::limit($row->organization_description, 20, ' ...');

                    return $organization_description;
                })
                ->editColumn('organization_url', function ($row) {
                    return '<a href="' . $row->organization_url . '" target="_blank" style="text-decoration: underline;color: #0097c9;">' . $row->organization_url . '</a>';
                })
                ->editColumn('organization_name', function ($row) {
                    return '<a href="/organizations/' . $row->organization_recordid . '" target="_blank" style="text-decoration: underline;color: #0097c9;">' . $row->organization_name . '</a>';
                })
                ->editColumn('organization_status_x', function ($row) {
                    return $row->status_data ? $row->status_data->status : '';
                })
                ->editColumn('last_verified_by', function ($row) {
                    return $row->get_last_verified_by ? $row->get_last_verified_by->first_name . ' ' . $row->get_last_verified_by->last_name : '';
                })
                ->editColumn('updated_by', function ($row) {
                    return $row->get_latest_updated($row, 'updated_by');
                    // return $row->get_updated_by ? $row->get_updated_by->first_name . ' ' . $row->get_updated_by->last_name : '';
                })
                ->editColumn('latest_updated_date', function ($row) {
                    $row->get_latest_updated($row, 'updated_at');

                    return date('Y-m-d H:i:s', strtotime($row->latest_updated_date));
                })
                ->addColumn('last_note_date', function ($row) {
                    $note = SessionData::where('session_organization', $row->organization_recordid)->orderBy('id', 'desc')->first();

                    return $note ? '<a href="/organization_notes/' . $row->organization_recordid . '"> ' . $note->created_at . '</a>' : "";
                })
                ->addColumn('last_edit_date', function ($row) {
                    $audit = Audit::where('auditable_id', $row->organization_recordid)->orderBy('id', 'desc')->first();

                    return $audit ? '<a href="/organization_edits/0/' . $row->organization_recordid . '"> ' . $audit->created_at . '</a>' : "";
                })
                ->addColumn('service_tag', function ($row) {
                    $tags = [];
                    if (isset($row->services)) {
                        $services = $row->services;
                        foreach ($services as $key => $value) {
                            if ($value->service_tag) {
                                $tagsArray = explode(',', $value->service_tag);
                                $tagsArray = is_array($tagsArray) ? array_unique($tagsArray) : [];
                                foreach ($tagsArray as $key => $value1) {
                                    $service_tag = ServiceTag::whereId($value1)->first();
                                    if ($service_tag && !in_array($service_tag->tag, $tags)) {
                                        $tags[] = $service_tag->tag;
                                    }
                                }
                            }
                        }
                    }
                    return count($tags) > 0 ? implode(',', $tags) : '';
                })
                ->editColumn('organization_tag', function ($row) {
                    $organization_tag = [];
                    if ($row->organization_tag) {
                        $organization_tags = explode(',', $row->organization_tag);
                        foreach ($organization_tags as $key => $value) {
                            $tag = OrganizationTag::whereId($value)->first();
                            if ($tag) {
                                $organization_tag[] = '<a href="javascript:void(0)" data-id="' . $row->organization_recordid . '" class="organizationTags" data-tags="' .  $row->organization_tag . '"> ' . $tag->tag . '</a>';
                            }
                        }
                        return implode(',', $organization_tag);
                    } else {
                        return '<button type="button" class="btn btn-success  waves-effect waves-classic organizationTags" data-id="' . $row->organization_recordid . '" data-tags="">Add Tag</button>';
                    }
                })
                ->editColumn('organization_status_x', function ($row) {

                    $organization_status = [];
                    if ($row->organization_status_x) {
                        $organization_statuses = explode(',', $row->organization_status_x);
                        foreach ($organization_statuses as $key => $value) {
                            $status = OrganizationStatus::whereId($value)->first();
                            if ($status) {
                                $organization_status[] = '<a href="javascript:void(0)" data-id="' . $row->organization_recordid . '" class="organizationStatuses" data-status="' .  $status->id . '"> ' . $status->status . '</a>';
                            }
                        }
                        return implode(',', $organization_status);
                    } else {
                        return '<button type="button" class="btn btn-danger  waves-effect waves-classic  organizationStatuses" data-id="' . $row->organization_recordid . '" data-status="">Add Status</button>';
                    }
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');

                    if ($extraData) {

                        if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                            $organization_tags = count($extraData['organization_tag']) > 0 ?  array_filter($extraData['organization_tag']) : [];
                            $query = $query->where(function ($q) use ($organization_tags) {
                                foreach ($organization_tags as $key => $value) {
                                    $q->orWhere('organization_tag', 'LIKE', '%' . $value . '%');
                                }
                            });
                        }
                        if (isset($extraData['organization_bookmark_only']) && $extraData['organization_bookmark_only'] != null && $extraData['organization_bookmark_only'] == "true") {
                            $query = $query->where('bookmark', 1);
                        }
                        if (isset($extraData['service_tag']) && $extraData['service_tag'] != null) {
                            $service_tags = count($extraData['service_tag']) > 0 ?  array_filter($extraData['service_tag']) : [];

                            $organization_recordids = Service::where(function ($q) use ($service_tags) {
                                foreach ($service_tags as $key => $value) {
                                    $q->orWhere('service_tag', 'LIKE', '%' . $value . '%');
                                }
                            })->pluck('service_organization')->toArray();
                            $query->whereIn('organization_recordid', $organization_recordids);
                        }
                        // last updated
                        if (isset($extraData['start_updated']) && $extraData['start_updated'] != null && $extraData['end_updated'] == null) {
                            $query = $query->whereDate('latest_updated_date', '>=', $extraData['start_updated']);
                        }
                        if (isset($extraData['start_updated']) && $extraData['start_updated'] != null && isset($extraData['end_updated']) && $extraData['end_updated'] != null) {
                            $query = $query->whereDate('latest_updated_date', '<=', $extraData['end_updated'])->whereDate('latest_updated_date', '>=', $extraData['start_updated']);
                        }
                        // end

                        // last verified
                        if (isset($extraData['start_verified']) && $extraData['start_verified'] != null && $extraData['end_verified'] == null) {
                            $query = $query->whereDate('last_verified_at', '>=', $extraData['start_verified']);
                        }
                        if (isset($extraData['start_verified']) && $extraData['start_verified'] != null && isset($extraData['end_verified']) && $extraData['end_verified'] != null) {
                            $query = $query->whereDate('last_verified_at', '<=', $extraData['end_verified'])->whereDate('last_verified_at', '>=', $extraData['start_verified']);
                        }
                        // end
                        // if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                        //     $query = $query->whereDate('updated_at', '<=', $extraData['end_date']);
                        // }
                        if (isset($extraData['status']) && $extraData['status'] != null) {
                            // $statuses = count($extraData['status']) > 0 ?  array_filter($extraData['status']) : [];
                            // $query = $query->where(function ($q) use ($statuses) {
                            //     foreach ($statuses as $key => $value) {
                            //         $q->orWhere('organization_status_x', 'LIKE', '%' . $value . '%');
                            //     }
                            // });
                            $query->whereIn('organization_status_x', $extraData['status']);
                        }
                        if (isset($extraData['last_updated_by']) && $extraData['last_updated_by'] != null) {
                            $query->where('updated_by', $extraData['last_updated_by']);
                        }
                        if (isset($extraData['last_verified_by']) && $extraData['last_verified_by'] != null) {
                            $query->where('last_verified_by', $extraData['last_verified_by']);
                        }
                    }
                    return $query;
                }, true)
                ->rawColumns(['services', 'phones', 'location', 'organization_details', 'contact_name', 'last_note_date', 'last_edit_date', 'organization_name', 'organization_tag', 'organization_url', 'bookmark', 'organization_status_x'])
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in organization table index : ' . $th);
            return redirect()->back();
        }
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
    public function export_tracking(Request $request)
    {
        try {
            // return Excel::download(new CodeExport, 'codes.csv');
            Excel::store(new TrackingExport($request), 'trackings.csv', 'csv');
            return response()->json([
                'path' => url('/csv/trackings.csv'),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in service code export : ' . $th);
            return response()->json([
                'path' => '',
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
