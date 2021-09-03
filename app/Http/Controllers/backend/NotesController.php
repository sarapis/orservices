<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Organization;
use App\Model\SessionData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = 0;
        $session_datas = SessionData::select('*');
        $organizations = Organization::pluck('organization_name', 'organization_recordid');
        $users = User::pluck('first_name', 'id');
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];

        if (!$request->ajax()) {
            return view('backEnd.notes.index', compact('session_datas', 'organizations', 'disposition_list', 'method_list', 'users', 'id'));
        }
        return DataTables::of($session_datas->orderBy('id', 'desc'))
            ->addColumn('user', function ($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->lsst_name : '';
            })
            ->editColumn('created_at', function ($row) {
                return date('d-m-Y H:i:s', strtotime($row->created_at));
            })
            ->editColumn('session_organization', function ($row) {
                return $row->organization ? $row->organization->organization_name : '';
            })
            ->filter(function ($query) use ($request) {
                $extraData = $request->get('extraData');

                if ($extraData) {

                    if (isset($extraData['organization']) && $extraData['organization'] != null) {
                        // $extraData['organization'] = count($extraData['organization']) > 0 ?  array_filter($extraData['organization']) : [];
                        $query = $query->where('session_organization', $extraData['organization']);
                    }
                    if (isset($extraData['session_method']) && $extraData['session_method'] != null) {
                        $query = $query->where('session_method', $extraData['session_method']);
                    }
                    if (isset($extraData['session_disposition']) && $extraData['session_disposition'] != null) {
                        $query = $query->where('session_disposition', $extraData['session_disposition']);
                    }
                    if (isset($extraData['user']) && $extraData['user'] != null) {
                        $query = $query->where('session_performed_by', $extraData['user']);
                    }
                    if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
                        $query = $query->whereDate('session_start_datetime', '>=', $extraData['start_date']);
                    }
                    if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                        $query = $query->whereDate('session_end_datetime', '<=', $extraData['end_date']);
                    }
                    if (isset($extraData['parent']) && $extraData['parent'] != null) {
                        $extraData['parent'] = count($extraData['parent']) > 0 ?  array_filter($extraData['parent']) : [];
                        if (in_array('none', $extraData['parent'])) {
                            $query = $query->whereNull('parent');
                        } elseif (in_array('all', $extraData['parent'])) {
                            $query = $query->whereNotNull('parent');
                        } else {
                            $query = $query->whereIn('parent', $extraData['parent']);
                        }
                    }
                }
                return $query;
            })
            ->make(true);
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
    public function userNotes(Request $request, $id)
    {
        try {
            $session_datas = SessionData::select('*');
            $organizations = Organization::pluck('organization_name', 'organization_recordid');
            $users = User::pluck('first_name', 'id');
            $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];

            if (!$request->ajax()) {
                return view('backEnd.notes.index', compact('session_datas', 'organizations', 'disposition_list', 'method_list', 'users', 'id'));
            }
            return DataTables::of($session_datas)
                ->addColumn('user', function ($row) {
                    return $row->user ? $row->user->first_name . ' ' . $row->user->lsst_name : '';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->created_at));
                })
                ->editColumn('session_organization', function ($row) {
                    return $row->organization ? $row->organization->organization_name : '';
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');

                    if ($extraData) {

                        if (isset($extraData['organization']) && $extraData['organization'] != null) {
                            // $extraData['organization'] = count($extraData['organization']) > 0 ?  array_filter($extraData['organization']) : [];
                            $query = $query->where('session_organization', $extraData['organization']);
                        }
                        if (isset($extraData['session_method']) && $extraData['session_method'] != null) {
                            $query = $query->where('session_method', $extraData['session_method']);
                        }
                        if (isset($extraData['session_disposition']) && $extraData['session_disposition'] != null) {
                            $query = $query->where('session_disposition', $extraData['session_disposition']);
                        }
                        if (isset($extraData['user']) && $extraData['user'] != null) {
                            $query = $query->where('session_performed_by', $extraData['user']);
                        }
                        if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
                            $query = $query->whereDate('session_start_datetime', '>=', $extraData['start_date']);
                        }
                        if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                            $query = $query->whereDate('session_end_datetime', '<=', $extraData['end_date']);
                        }
                        if (isset($extraData['parent']) && $extraData['parent'] != null) {
                            $extraData['parent'] = count($extraData['parent']) > 0 ?  array_filter($extraData['parent']) : [];
                            if (in_array('none', $extraData['parent'])) {
                                $query = $query->whereNull('parent');
                            } elseif (in_array('all', $extraData['parent'])) {
                                $query = $query->whereNotNull('parent');
                            } else {
                                $query = $query->whereIn('parent', $extraData['parent']);
                            }
                        }
                    }
                    return $query;
                })
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in user notes : ' . $th);
        }
    }
}
