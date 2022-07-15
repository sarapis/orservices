<?php

namespace App\Http\Controllers\backend;

use App\Exports\EditExport;
use App\Http\Controllers\Controller;
use App\Model\Contact;
use App\Model\Location;
use App\Model\Organization;
use App\Model\OrganizationTag;
use App\Model\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\Facades\DataTables;

class EditsController extends Controller
{

    public function __construct(AuditsController $auditsController)
    {
        $this->auditsController = $auditsController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = 0, $organization_id = 0)
    {
        try {
            $audits = Audit::orderBy('id', 'desc')->cursor();

            $fieldTypesData = '';
            if ($request->get('extraData')) {
                $extraData = $request->get('extraData');
                if (isset($extraData['fieldType']) && $extraData['fieldType'] != null) {
                    $fieldTypesData = $extraData['fieldType'];
                }
                $audits =  $this->filterData($request);
            }
            $data = [];
            $i = 0;
            // dd($audits);
            $fieldTypes = [];
            $audits = $this->auditsController->filterAudits($audits);
            foreach ($audits as $key => $audit) {
                foreach ($audit->old_values as $key1 => $value1) {
                    if ($fieldTypesData && $fieldTypesData == $key1) {
                        $fieldTypes[] = $key1;
                        $data[$i]['id'] = $audit->id;
                        $data[$i]['created_at'] = date('d-m-Y', strtotime($audit->created_at));
                        $data[$i]['time'] = date('H:i:s', strtotime($audit->created_at));
                        $data[$i]['auditable_id'] = $audit->event == 'updated' ? '<a href="/viewChanges/' . $audit->id . '/' . $audit->auditable_id . '">' . $audit->auditable_id . '</a>' : '';
                        $data[$i]['auditable_type'] = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                        $data[$i]['user'] = $audit->user ? $audit->user->first_name . ' ' . $audit->user->last_name : '';
                        $data[$i]['user_type'] = $audit->user_type;
                        $data[$i]['event'] = $audit->event;
                        $data[$i]['field_type'] = $key1;
                        $data[$i]['change_from'] = $value1;
                        $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                        $organization = null;
                        if ($modal == 'Organization') {
                            if ($audit->event == 'created') {
                                $organization = Organization::whereId($audit->auditable_id)->first();
                            } else {
                                $organization = Organization::where('organization_recordid', $audit->auditable_id)->first();
                            }
                        } elseif ($modal == 'Location') {
                            if ($audit->event == 'created') {
                                $location = Location::whereId($audit->auditable_id)->first();
                            } else {
                                $location = Location::where('location_recordid', $audit->auditable_id)->first();
                            }
                            $organization = $location->organization;
                        } elseif ($modal == 'Contact') {
                            if ($audit->event == 'created') {
                                $contact = Contact::whereId($audit->auditable_id)->first();
                            } else {
                                $contact = Contact::where('contact_recordid', $audit->auditable_id)->first();
                            }
                            $organization = $contact->organization;
                        } elseif ($modal == 'Service') {
                            if ($audit->event == 'created') {
                                $service = Service::whereId($audit->auditable_id)->first();
                            } else {
                                $service = Service::where('service_recordid', $audit->auditable_id)->first();
                            }
                            $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                        }
                        // elseif ($modal == 'Schedule') {
                        //     if ($audit->event == 'created') {
                        //         $service = Service::whereId($audit->auditable_id)->first();
                        //     } else {
                        //         $service = Service::where('service_recordid', $audit->auditable_id)->first();
                        //     }
                        //     $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                        // }
                        $data[$i]['organization'] = $organization ? $organization->organization_name : '';
                        $data[$i]['change_to'] = $audit->new_values[$key1] ?? '';
                        // $dummy = new \App\Model\Area;
                        // $desiredResult = $dummy->newInstance($data, true);
                        $i++;
                    }
                    if ($fieldTypesData == '') {
                        $fieldTypes[] = $key1;
                        $data[$i]['id'] = $audit->id;
                        $data[$i]['created_at'] = date('d-m-Y', strtotime($audit->created_at));
                        $data[$i]['time'] = date('H:i:s', strtotime($audit->created_at));
                        $data[$i]['auditable_id'] = $audit->event == 'updated' ? '<a href="/viewChanges/' . $audit->id . '/' . $audit->auditable_id . '">' . $audit->auditable_id . '</a>' : '';
                        $data[$i]['auditable_type'] = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                        $data[$i]['user'] = $audit->user ? $audit->user->first_name . ' ' . $audit->user->last_name : '';
                        $data[$i]['user_type'] = $audit->user_type;
                        $data[$i]['event'] = $audit->event;
                        $data[$i]['field_type'] = $key1;
                        $data[$i]['change_from'] = $value1;
                        $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                        $organization = null;
                        if ($modal == 'Organization') {
                            if ($audit->event == 'created') {
                                $organization = Organization::find($audit->auditable_id);
                            } else {
                                $organization = Organization::where('organization_recordid', $audit->auditable_id)->first();
                            }
                        } elseif ($modal == 'Location') {
                            // if ($audit->event == 'created') {
                            //     $location = Location::whereId($audit->auditable_id)->first();
                            // } else {
                            $location = Location::where('location_recordid', $audit->auditable_id)->first();
                            // }*
                            $organization = $location ? $location->organization : null;
                        } elseif ($modal == 'Contact') {
                            if ($audit->event == 'created') {
                                $contact = Contact::find($audit->auditable_id);
                            } else {
                                $contact = Contact::where('contact_recordid', $audit->auditable_id)->first();
                            }
                            $organization = $contact ? $contact->organization : null;
                        } elseif ($modal == 'Service') {
                            if ($audit->event == 'created') {
                                $service = Service::whereId($audit->auditable_id)->first();
                            } else {
                                $service = Service::where('service_recordid', $audit->auditable_id)->first();
                            }

                            $organization = isset($service->getOrganizations) && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                        }
                        //  elseif ($modal == 'Schedule') {
                        //     if ($audit->event == 'created') {
                        //         $service = Service::whereId($audit->auditable_id)->first();
                        //     } else {
                        //         $service = Service::where('service_recordid', $audit->auditable_id)->first();
                        //     }
                        //     $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                        // }
                        $data[$i]['organization'] = $organization ? $organization->organization_name : '';
                        $data[$i]['change_to'] = $audit->new_values[$key1] ?? '';
                        // $dummy = new \App\Model\Area();
                        // $desiredResult = $dummy->newInstance($data[$i], false);
                        $i++;
                    }
                }
            }
            $fieldTypes = array_unique(array_filter($fieldTypes));
            $organizations = Organization::pluck('organization_name', 'organization_recordid');
            $users = User::pluck('first_name', 'id');
            $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $dataTypes = ['Organization', 'Contact', 'Location', 'Service', 'Schedule', 'Phone'];

            $organization_tags = OrganizationTag::orderBy('order')->pluck('tag', 'id');
            // dd($selected_organization);
            // $tag_list = [];
            // foreach ($organization_tags as $key => $value) {
            //     $tags = explode(",", trim($value->organization_tag));
            //     $tag_list = array_merge($tag_list, $tags);
            // }
            // $tag_list = array_unique($tag_list);
            // $organization_tagsArray = [];
            // foreach ($tag_list as $key => $value) {
            //     $organization_tagsArray[$value] = $value;
            // }
            // dd($desiredResult);
            if (!$request->ajax()) {
                return view('backEnd.edits.index', compact('organizations', 'disposition_list', 'dataTypes', 'users', 'id', 'organization_tags', 'fieldTypes', 'organization_id'));
            }
            return DataTables::of(($data))
                // ->orderColumn('created_at', function ($query, $keyword) {
                //     $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
                // })
                // ->addColumn('user', function ($row) {
                //     return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '';
                // })
                // ->addColumn('log', function ($row) {
                //     // $data = $row->created_at . ': ' . ($row->user ? $row->user->first_name . ' ' . $row->user->last_name : '') . $row->event . ' data_type : ';
                //     $data = '';
                //     foreach ($row->old_values as $key => $v) {
                //         $fieldNameArray = explode('_', $key);
                //         $fieldName = implode(' ', $fieldNameArray);
                //         $data .= ' ' . ucfirst($fieldName) . ' , ';
                //     }
                //     return $data;
                // })
                // ->editColumn('auditable_type', function ($row) {
                //     return str_replace(trim('App\Model\ '), '', $row->auditable_type);
                // })
                // ->editColumn('auditable_id', function ($row) {
                //     return '<a href="/viewChanges/' . $row->id . '/' . $row->auditable_id . '">' . $row->auditable_id . '</a>';
                // })
                // ->editColumn('created_at', function ($row) {
                //     return date('d-m-Y H:i:s', strtotime($row->created_at));
                // })
                // ->editColumn('organization', function ($row) {
                //     $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                //     if ($modal == 'Organization') {
                //         $organization = Organization::where('organization_recordid', $row->auditable_id)->first();
                //     } elseif ($modal == 'Location') {
                //         $location = Location::where('location_recordid', $row->auditable_id)->first();
                //         $organization = $location->organization;
                //     } elseif ($modal == 'Contact') {
                //         $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                //         $organization = $contact->organization;
                //     } elseif ($modal == 'Service') {
                //         $service = Service::where('service_recordid', $row->auditable_id)->first();
                //         $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                //     }
                //     return $organization ? $organization->organization_name : '';
                // })
                // ->filter(function ($query) use ($request) {
                //     $extraData = $request->get('extraData');
                //     if ($extraData) {

                //         if (isset($extraData['organization']) && $extraData['organization'] != null) {
                //             // $extraData['organization'] = count($extraData['organization']) > 0 ?  array_filter($extraData['organization']) : [];
                //             $row = $query->first();
                //             $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                //             if ($modal == 'Organization') {
                //                 $organization = Organization::where('organization_recordid', $row->auditable_id)->first();
                //                 $auditableIds[] = $row->auditable_id;
                //             } elseif ($modal == 'Location') {
                //                 $location = Location::where('location_recordid', $row->auditable_id)->first();
                //                 $organization = $location->organization;
                //                 if ($organization->organization_recordid == $extraData['organization']) {
                //                     $auditableIds[] = $row->auditable_id;
                //                 }
                //             } elseif ($modal == 'Contact') {
                //                 $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                //                 $organization = $contact->organization;
                //                 if ($organization->organization_recordid == $extraData['organization']) {
                //                     $auditableIds[] = $row->auditable_id;
                //                 }
                //             } elseif ($modal == 'Service') {
                //                 $service = Service::where('service_recordid', $row->auditable_id)->first();
                //                 $organization = $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                //                 if ($organization->organization_recordid == $extraData['organization']) {
                //                     $auditableIds[] = $row->auditable_id;
                //                 }
                //             }
                //             $query = $query->whereIn('auditable_id', $auditableIds);
                //         }
                //         if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                //             $organization_ids = Organization::where('organization_tag', 'LIKE', '%' . $extraData['organization_tag'] . '%')->pluck('organization_recordid')->toArray();
                //             $query = $query->whereIn('auditable_id', $organization_ids);
                //         }
                //         if (isset($extraData['dataType']) && $extraData['dataType'] != null) {
                //             $query = $query->where('auditable_type', 'LIKE', '%' . $extraData['dataType'] . '%');
                //             // $query = $query->filter(function ($value, $key)  use ($extraData) {
                //             //     return $value->auditable_type == $extraData['dataType'];
                //             // });
                //         }
                //         if (isset($extraData['user']) && $extraData['user'] != null) {
                //             $query = $query->where('user_id', $extraData['user']);
                //         }
                //         if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
                //             $query = $query->whereDate('created_at', '>=', $extraData['start_date']);
                //         }
                //         if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                //             $query = $query->whereDate('created_at', '<=', $extraData['end_date']);
                //         }
                //     }
                //     return $query;
                // })
                ->rawColumns(['auditable_id'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in edits controller index : ' . $th);
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
            $audits = Audit::orderBy('id', 'desc');

            $organizations = Organization::pluck('organization_name', 'organization_recordid');
            $users = User::pluck('first_name', 'id');
            $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $dataTypes = ['Organization', 'Contact', 'Location', 'Service'];
            // $organization_tags = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();
            // // dd($selected_organization);
            // $tag_list = [];
            // foreach ($organization_tags as $key => $value) {
            //     $tags = explode(",", trim($value->organization_tag));
            //     $tag_list = array_merge($tag_list, $tags);
            // }
            // $tag_list = array_unique($tag_list);
            // $organization_tagsArray = [];
            // foreach ($tag_list as $key => $value) {
            //     $organization_tagsArray[$value] = $value;
            // }
            $organization_tagsArray = OrganizationTag::pluck('tag', 'id');
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
                        if ($row->event == 'created') {
                            $organization = Organization::whereId($row->auditable_id)->first();
                        } else {
                            $organization = Organization::where('organization_recordid', $row->auditable_id)->first();
                        }
                    } elseif ($modal == 'Location') {
                        if ($row->event == 'created') {
                            $location = Location::whereId($row->auditable_id)->first();
                        } else {
                            $location = Location::where('location_recordid', $row->auditable_id)->first();
                        }
                        $organization = $location->organization;
                    } elseif ($modal == 'Contact') {
                        if ($row->event == 'created') {
                            $contact = Contact::whereId($row->auditable_id)->first();
                        } else {
                            $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                        }
                        $organization = $contact->organization;
                    } elseif ($modal == 'Service') {
                        if ($row->event == 'created') {
                            $service = Service::whereId($row->auditable_id)->first();
                        } else {
                            $service = Service::where('service_recordid', $row->auditable_id)->first();
                        }
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
            Log::error('Error in edits useredit : ' . $th);
        }
    }
    public function filterData($request)
    {
        try {
            $extraData = $request->get('extraData');
            $query = Audit::orderBy('created_at', 'desc');
            $auditableIds = [];
            if (isset($extraData['organization']) && $extraData['organization'] != null) {
                foreach ($query->get() as $key => $value) {
                    $row = $value;
                    $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                    if ($modal == 'Organization') {
                        if ($row->auditable_id == $extraData['organization']) {
                            $organization = Organization::where('organization_recordid', $extraData['organization'])->first();
                            $auditableIds[] = $row->auditable_id;
                        }
                    } elseif ($modal == 'Location') {
                        if ($row->event == 'created') {
                            $location = Location::whereId($row->auditable_id)->first();
                        } else {
                            $location = Location::where('location_recordid', $row->auditable_id)->first();
                        }
                        $organization = $location ? $location->organization : '';
                        if ($organization && $organization->organization_recordid == $extraData['organization']) {
                            $auditableIds[] = $row->auditable_id;
                        }
                    } elseif ($modal == 'Contact') {
                        if ($row->event == 'created') {
                            $contact = Contact::whereId($row->auditable_id)->first();
                        } else {
                            $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                        }
                        $organization = $contact ? $contact->organization : '';
                        if ($organization && $organization->organization_recordid == $extraData['organization']) {
                            $auditableIds[] = $row->auditable_id;
                        }
                    } elseif ($modal == 'Service') {
                        if ($row->event == 'created') {
                            $service = Service::whereId($row->auditable_id)->first();
                        } else {
                            $service = Service::where('service_recordid', $row->auditable_id)->first();
                        }
                        $organization = $service && $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                        if ($organization && $organization->organization_recordid == $extraData['organization']) {
                            $auditableIds[] = $row->auditable_id;
                        }
                    }
                    $auditableIds = array_unique($auditableIds);
                }
                $query = $query->whereIn('auditable_id', $auditableIds);
            }
            if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                $organization_ids = Organization::where('organization_tag', 'LIKE', '%' . $extraData['organization_tag'] . '%')->pluck('organization_recordid')->toArray();
                //
                $organization_tagsId = [];
                foreach ($query->get() as $key => $value) {
                    $row = $value;
                    $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                    if ($modal == 'Organization') {
                        if (in_array($row->auditable_id, $organization_ids)) {
                            $organization_tagsId[] = $row->auditable_id;
                        }
                    } elseif ($modal == 'Location') {
                        if ($row->event == 'created') {
                            $location = Location::find($row->auditable_id);
                        } else {
                            $location = Location::where('location_recordid', $row->auditable_id)->first();
                        }
                        $organization = $location ? $location->organization : null;
                        if ($organization && in_array($organization->organization_recordid, $organization_ids)) {
                            $organization_tagsId[] = $row->auditable_id;
                        }
                    } elseif ($modal == 'Contact') {
                        if ($row->event == 'created') {
                            $contact = Contact::find($row->auditable_id);
                        } else {
                            $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                        }
                        $organization = $contact ? $contact->organization : '';
                        if ($organization && in_array($organization->organization_recordid, $organization_ids)) {
                            $organization_tagsId[] = $row->auditable_id;
                        }
                    } elseif ($modal == 'Service') {
                        if ($row->event == 'created') {
                            $service = Service::find($row->auditable_id);
                        } else {
                            $service = Service::where('service_recordid', $row->auditable_id)->first();
                        }
                        $organization = $service && $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                        if ($organization && in_array($organization->organization_recordid, $organization_ids)) {
                            $organization_tagsId[] = $row->auditable_id;
                        }
                    }
                    $organization_tagsId = array_unique($organization_tagsId);
                }
                $query = $query->whereIn('auditable_id', $organization_tagsId);
            }
            if (isset($extraData['dataType']) && $extraData['dataType'] != null) {
                $query = $query->where('auditable_type', 'LIKE', '%' . $extraData['dataType'] . '%');
                // $query = $query->filter(function ($value, $key)  use ($extraData) {
                //     return $value->auditable_type == $extraData['dataType'];
                // });
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
            return $query->get();
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in edit filterData : ' . $th);
        }
    }
    public function edits_export_csv(Request $request)
    {
        try {
            Excel::store(new EditExport($request), 'edits.csv', 'csv');
            return response()->json([
                'path' => url('/csv/edits.csv'),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in Edit export : ' . $th);
        }
    }
}
