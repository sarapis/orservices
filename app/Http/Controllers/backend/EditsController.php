<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Contact;
use App\Model\Location;
use App\Model\Organization;
use App\Model\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\Facades\DataTables;

class EditsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $id = 0;
            $audits = Audit::where('event', '!=', 'created')->where('old_values', '!=', '[]')->orderBy('created_at', 'desc');
            $organizations = Organization::pluck('organization_name', 'organization_name');
            $users = User::pluck('first_name', 'id');
            $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $dataTypes = ['Organization', 'Contact', 'Location', 'Service'];

            $organization_tags = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();
            // dd($selected_organization);
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
            if (!$request->ajax()) {
                return view('backEnd.edits.index', compact('organizations', 'disposition_list', 'dataTypes', 'users', 'id', 'organization_tagsArray'));
            }
            return DataTables::of($audits)
                ->addColumn('user', function ($row) {
                    return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '';
                })
                ->addColumn('log', function ($row) {
                    // $data = $row->created_at . ': ' . ($row->user ? $row->user->first_name . ' ' . $row->user->last_name : '') . $row->event . ' data_type : ';
                    $data = '';
                    foreach ($row->old_values as $key => $v) {
                        $fieldNameArray = explode('_', $key);
                        $fieldName = implode(' ', $fieldNameArray);
                        $data .= ' ' . ucfirst($fieldName) . ' , ';
                    }
                    return $data;
                })
                ->editColumn('auditable_type', function ($row) {
                    return str_replace(trim('App\Model\ '), '', $row->auditable_type);
                })
                ->editColumn('auditable_id', function ($row) {
                    return '<a href="/viewChanges/' . $row->id . '/' . $row->auditable_id . '">' . $row->auditable_id . '</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->created_at));
                })
                ->editColumn('organization', function ($row) {
                    $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                    if ($modal == 'Organization') {
                        $organization = Organization::where('organization_recordid', $row->auditable_id)->first();
                    } elseif ($modal == 'Location') {
                        $location = Location::where('location_recordid', $row->auditable_id)->first();
                        $organization = $location->organization;
                    } elseif ($modal == 'Contact') {
                        $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                        $organization = $contact->organization;
                    } elseif ($modal == 'Service') {
                        $service = Service::where('service_recordid', $row->auditable_id)->first();
                        $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                    }
                    return $organization ? $organization->organization_name : '';
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');
                    if ($extraData) {

                        if (isset($extraData['organization']) && $extraData['organization'] != null) {
                            // $extraData['organization'] = count($extraData['organization']) > 0 ?  array_filter($extraData['organization']) : [];
                            $row = $query->first();
                            $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                            if ($modal == 'Organization') {
                                $organization = Organization::where('organization_recordid', $row->auditable_id)->first();
                                $auditableIds[] = $row->auditable_id;
                            } elseif ($modal == 'Location') {
                                $location = Location::where('location_recordid', $row->auditable_id)->first();
                                $organization = $location->organization;
                                if ($organization->organization_recordid == $extraData['organization']) {
                                    $auditableIds[] = $row->auditable_id;
                                }
                            } elseif ($modal == 'Contact') {
                                $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                                $organization = $contact->organization;
                                if ($organization->organization_recordid == $extraData['organization']) {
                                    $auditableIds[] = $row->auditable_id;
                                }
                            } elseif ($modal == 'Service') {
                                $service = Service::where('service_recordid', $row->auditable_id)->first();
                                $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                                if ($organization->organization_recordid == $extraData['organization']) {
                                    $auditableIds[] = $row->auditable_id;
                                }
                            }
                            $query = $query->whereIn('auditable_id', $auditableIds);
                        }
                        if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                            $organization_ids = Organization::where('organization_tag', 'LIKE', '%' . $extraData['organization_tag'] . '%')->pluck('organization_recordid')->toArray();
                            $query = $query->whereIn('auditable_id', $organization_ids);
                        }
                        if (isset($extraData['dataType']) && $extraData['dataType'] != null) {
                            $query = $query->where('auditable_type', 'LIKE', '%' . $extraData['dataType'] . '%');
                        }
                        if (isset($extraData['user']) && $extraData['user'] != null) {
                            $query = $query->where('user_id', $extraData['user']);
                        }
                        if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
                            $query = $query->whereDate('created_at', '>=', $extraData['start_date']);
                        }
                        if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                            $query = $query->whereDate('created_at', '<=', $extraData['end_date']);
                        }
                    }
                    return $query;
                })
                ->rawColumns(['auditable_id'])
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
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
    public function userEdits(Request $request, $id)
    {
        try {
            $audits = Audit::orderBy('id', 'desc')->all();
            $organizations = Organization::pluck('organization_name', 'organization_recordid');
            $users = User::pluck('first_name', 'id');
            $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $dataTypes = ['Organization', 'Contact', 'Location', 'Service'];
            $organization_tags = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();
            // dd($selected_organization);
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
            if (!$request->ajax()) {
                return view('backEnd.edits.index', compact('organizations', 'disposition_list', 'dataTypes', 'users', 'id', 'organization_tagsArray'));
            }
            return DataTables::of($audits)
                ->addColumn('user', function ($row) {
                    return $row->user ? $row->user->first_name . ' ' . $row->user->lsst_name : '';
                })
                ->addColumn('log', function ($row) {
                    // $data =  $row->created_at . ': '  . ($row->user ? $row->user->first_name . ' ' . $row->user->last_name : '') . $row->event . ' data_type : ';
                    $data = '';
                    foreach ($row->old_values as $key => $v) {
                        $fieldNameArray = explode('_', $key);
                        $fieldName = implode(' ', $fieldNameArray);
                        $data .= ' ' . ucfirst($fieldName) . ' , ';
                    }
                    return $data;
                })
                ->editColumn('auditable_type', function ($row) {
                    return str_replace(trim('App\Model\ '), '', $row->auditable_type);
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->created_at));
                })
                ->editColumn('organization', function ($row) {
                    $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                    if ($modal == 'Organization') {
                        $organization = Organization::where('organization_recordid', $row->auditable_id)->first();
                    } elseif ($modal == 'Location') {
                        $location = Location::where('location_recordid', $row->auditable_id)->first();
                        $organization = $location->organization;
                    } elseif ($modal == 'Contact') {
                        $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                        $organization = $contact->organization;
                    } elseif ($modal == 'Service') {
                        $service = Service::where('service_recordid', $row->auditable_id)->first();
                        $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                    }
                    return $organization ? $organization->organization_name : '';
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');
                    if ($extraData) {

                        if (isset($extraData['organization']) && $extraData['organization'] != null) {
                            // $extraData['organization'] = count($extraData['organization']) > 0 ?  array_filter($extraData['organization']) : [];
                            $query = $query->where('auditable_id', $extraData['organization']);
                        }
                        if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                            $organization_ids = Organization::where('organization_tag', 'LIKE', '%' . $extraData['organization_tag'] . '%')->pluck('organization_recordid')->toArray();
                            $query = $query->whereIn('auditable_id', $organization_ids);
                        }
                        if (isset($extraData['dataType']) && $extraData['dataType'] != null) {
                            $query = $query->where('auditable_type', 'LIKE', '%' . $extraData['dataType'] . '%');
                        }
                        if (isset($extraData['user']) && $extraData['user'] != null) {
                            $query = $query->where('user_id', $extraData['user']);
                        }
                        if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
                            $query = $query->whereDate('created_at', '>=', $extraData['start_date']);
                        }
                        if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                            $query = $query->whereDate('created_at', '<=', $extraData['end_date']);
                        }
                    }
                    return $query;
                })
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
