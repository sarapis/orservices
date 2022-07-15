<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Disposition;
use App\Model\InteractionMethod;
use App\Model\Organization;
use App\Model\OrganizationStatus;
use App\Model\Service;
use App\Model\ServiceStatus;
use App\Model\SessionData;
use App\Model\SessionInteraction;
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
        $organization_id = 0;
        $session_datas = SessionData::select('*');
        $organizations = Organization::pluck('organization_name', 'organization_recordid');
        $services = Service::orderBy('service_name')->pluck('service_name', 'service_recordid');
        $organization_status = OrganizationStatus::pluck('status', 'id');
        $service_statuses = ServiceStatus::pluck('status', 'id');
        $users = User::pluck('first_name', 'id');
        $disposition_list = Disposition::pluck('name', 'id');
        $method_list = InteractionMethod::pluck('name', 'id');

        return view('backEnd.notes.index', compact('session_datas', 'organizations', 'disposition_list', 'method_list', 'users', 'id', 'organization_id', 'organization_status', 'service_statuses', 'services'));
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
            $organization_id = 0;
            $organizations = Organization::pluck('organization_name', 'organization_recordid');
            $users = User::pluck('first_name', 'id');
            $organization_status = OrganizationStatus::pluck('status', 'id');
            $services = Service::pluck('service_name', 'service_recordid');
            $service_statuses = ServiceStatus::pluck('status', 'id');
            // $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $disposition_list = Disposition::pluck('name', 'id');
            $method_list = InteractionMethod::pluck('name', 'id');

            return view('backEnd.notes.index', compact('session_datas', 'organizations', 'disposition_list', 'method_list', 'users', 'id', 'organization_id', 'organization_status', 'service_statuses', 'services'));
        } catch (\Throwable $th) {
            Log::error('Error in user notes : ' . $th);
        }
    }
    public function organization_notes(Request $request, $organization_id)
    {
        try {
            $id = 0;
            $organizations = Organization::pluck('organization_name', 'organization_recordid');
            $users = User::pluck('first_name', 'id');
            $organization_status = OrganizationStatus::pluck('status', 'id');
            $services = Service::pluck('service_name', 'service_recordid');
            $service_statuses = ServiceStatus::pluck('status', 'id');
            $disposition_list = Disposition::pluck('name', 'id');
            $method_list = InteractionMethod::pluck('name', 'id');
            $session_datas = SessionData::select('*');

            return view('backEnd.notes.index', compact('session_datas', 'organizations', 'disposition_list', 'method_list', 'users', 'organization_id', 'id', 'organization_status', 'service_statuses', 'services'));
        } catch (\Throwable $th) {
            Log::error('Error in user notes : ' . $th);
        }
    }
    public function get_session_record(Request $request)
    {
        $session_datas = SessionData::select('*');
        return DataTables::of($session_datas)
            ->addColumn('user', function ($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->lsst_name : '';
            })
            ->editColumn('created_at', function ($row) {
                return date('d-m-Y H:i:s', strtotime($row->created_at));
            })
            ->editColumn('organization_status', function ($row) {
                return $row->organization && $row->organization->status_data ? $row->organization->status_data->status : '';
            })
            ->editColumn('session_organization', function ($row) {
                return $row->organization ? $row->organization->organization_name : '';
            })
            ->editColumn('session_method', function ($row) {
                return $row->get_interaction_method_data ? $row->get_interaction_method_data->name : '';
            })
            ->editColumn('session_service', function ($row) {
                return $row->service ? $row->service->service_name : '';
            })
            ->editColumn('session_disposition', function ($row) {
                return $row->get_disposition_data ? $row->get_disposition_data->name : '';
            })
            ->editColumn('service_status', function ($row) {
                return $row->service && $row->service->get_status ? $row->service->get_status->status : '';
            })
            ->filter(function ($query) use ($request) {
                $extraData = $request->get('extraData');

                if ($extraData) {

                    if (isset($extraData['organization']) && $extraData['organization'] != null) {
                        // $extraData['organization'] = count($extraData['organization']) > 0 ?  array_filter($extraData['organization']) : [];
                        $query = $query->where('session_organization', $extraData['organization']);
                    }
                    if (isset($extraData['session_service']) && $extraData['session_service'] != null) {
                        $query = $query->where('session_service', $extraData['session_service']);
                    }
                    if (isset($extraData['service_status']) && $extraData['service_status'] != null) {
                        $query = $query->where('service_status', $extraData['service_status']);
                    }
                    if (isset($extraData['organization_status']) && $extraData['organization_status'] != null) {
                        $organization_recordids = Organization::where('organization_status_x', $extraData['organization_status'])->pluck('organization_recordid')->toArray();
                        $query = $query->whereIn('session_organization', $organization_recordids);
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
}
