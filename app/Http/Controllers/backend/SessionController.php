<?php

namespace App\Http\Controllers\backend;

use App\Exports\InteractionExport;
use App\Exports\SessionExport;
use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Organization;
use App\Model\SessionData;
use App\Model\SessionInteraction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $layout = Layout::find(1);
            $sessions = SessionData::select('*');
            $organization_recordedid = SessionData::select("session_organization")->distinct()->get();
            $organizations = Organization::whereIn('organization_recordid', $organization_recordedid)->pluck('organization_name', 'organization_recordid');
            // $organization_tags = Organization::whereNotNull('organization_tag')->whereIn('organization_recordid', $organization_recordedid)->pluck('organization_tag', 'organization_recordid');

            $organization_tags = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();

            $tag_list = [];
            foreach ($organization_tags as $key => $value) {
                $tags = explode(", ", trim($value->organization_tag));
                $tag_list = array_merge($tag_list, $tags);
            }
            $tag_list = array_unique($tag_list);
            $organization_tagsArray = [];
            foreach ($tag_list as $key => $value) {
                $organization_tagsArray[$value] = $value;
            }

            $session_starts = SessionData::pluck('session_start_datetime', 'session_recordid');
            $session_ends = SessionData::pluck('session_end_datetime', 'session_recordid');

            if (!$request->ajax()) {
                return View('backEnd.sessions.index', compact('layout', 'sessions', 'organizations', 'organization_tagsArray'));
            }
            return DataTables::of($sessions->orderBy('id', 'desc'))
                // ->addColumn('action', function ($row) {
                //     $link = '<button type="button" class="btn btn-danger ignoreButton" data-id="' . $row->id . '">Ignore</button>';
                //     return $link;
                // })
                ->editColumn('session_recordid', function ($row) {
                    $link = '<a href="#" data-id="' . $row->session_recordid . '" style="color:blue;" data-toggle="modal" data-target="#interactionModal" class="interactionLog">' . $row->session_recordid . '</a>';
                    return $link;
                })
                ->editColumn('session_performed_by', function ($row) {
                    return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '';
                })
                ->editColumn('session_organization', function ($row) {
                    return $row->organization ? $row->organization->organization_name : '';
                })
                ->addColumn('organization_tags', function ($row) {

                    return $row->organization ? $row->organization->organization_tag : '';
                })
                ->editColumn('created_at', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');
                    if ($extraData) {
                        if (isset($extraData['organization']) && $extraData['organization'] != null) {
                            $session_organization = explode("|", $extraData['organization']);

                            $query = $query->whereIn('session_organization', $session_organization);
                        }
                        if (isset($extraData['organization_tags']) && $extraData['organization_tags'] != null) {
                            $session_organization = explode("|", $extraData['organization_tags']);
                            $oraganization_recordids = Organization::select('organization_recordid')
                                ->Where(function ($query) use ($session_organization) {
                                    for ($i = 0; $i < count($session_organization); $i++) {
                                        $query->orwhere('organization_tag', 'like',  '%' . $session_organization[$i] . '%');
                                    }
                                })->get();
                            $oraganization_recordids_array = [];
                            foreach ($oraganization_recordids as $key => $value) {
                                $oraganization_recordids_array[] = $value->organization_recordid;
                            }
                            $query = $query->whereIn('session_organization', $oraganization_recordids_array);
                        }
                        if (isset($extraData['start_date']) && isset($extraData['end_date']) && $extraData['end_date'] != null && $extraData['start_date'] != null) {

                            $start_date = $extraData['start_date'];
                            $end_date = $extraData['end_date'];


                            $query = $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($start_date)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($end_date)));
                        }
                    }
                })
                ->rawColumns(['session_recordid'])
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
    public function all_session_export(Request $request)
    {
        try {
            return Excel::download(new SessionExport($request), 'Sessions.csv');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function all_interaction_export(Request $request)
    {
        try {
            return Excel::download(new InteractionExport($request), 'SessionInteraction.csv');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getInteraction(Request $request)
    {
        try {
            $id = $request->id;
            $interactionLogs = SessionInteraction::where('interaction_session', $id)->get();

            return response()->json([
                'data' => $interactionLogs,
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
