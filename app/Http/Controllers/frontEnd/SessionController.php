<?php

namespace App\Http\Controllers\frontEnd;

use App\Exports\InteractionExport;
use App\Http\Controllers\Controller;
use App\Model\Map;
use Illuminate\Http\Request;
use App\Model\SessionData;
use App\Model\SessionInteraction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sentinel;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Exports\SessionExport;
use App\Model\Organization;
use App\Model\OrganizationStatus;
use App\Model\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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


    // public function create_in_organization($id)
    // {
    //     $map = Map::find(1);
    //     $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
    //     $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
    //     $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
    //     $session_status_list = ['Success', 'Partial Success', 'Unable to verify', 'Out of business'];

    //     return view('frontEnd.session-create-in-organization', compact('map', 'organization', 'disposition_list', 'method_list', 'session_status_list'));
    // }

    public function create_in_organization($id)
    {
        $session = new SessionData;
        $new_recordid = SessionData::max('session_recordid') + 1;
        $session->session_recordid = $new_recordid;
        $user = Auth::user();
        $date_time = date("Y-m-d h:i:sa");
        $session->session_name = 'session' . $new_recordid;
        $session->session_organization = $id;

        if ($user) {
            $session->session_performed_by = $user->id;
        }

        $session->session_performed_at = Carbon::now();
        $session->session_edits = '0';

        $session->save();

        $map = Map::find(1);

        return redirect('session/' . $new_recordid);
    }


    public function add_new_session_in_organization(Request $request)
    {
        $session = new SessionData();

        $new_recordid = SessionData::max('session_recordid') + 1;
        $session->session_recordid = $new_recordid;

        $user = Auth::user();
        $date_time = date("Y-m-d h:i:sa");

        $session->session_name = $request->session_name;
        $session->session_method = $request->session_method;
        $session->session_disposition = $request->session_disposition;
        $session->session_notes = $request->session_notes;
        $session->session_edits = $request->session_records_edited;

        $session_organization_id = $request->session_organization;
        $session->session_organization = $session_organization_id;

        if ($user) {
            $session->session_performed_by = $user->id;
        }

        $session->session_performed_at = $date_time;

        $session->save();

        return redirect('organizations/' . $session_organization_id);
    }

    public function add_interaction(Request $request)
    {
        $interaction = new SessionInteraction();
        $session_recordid = $request->session_recordid;
        $interaction->interaction_session = $request->session_recordid;

        $new_recordid = SessionInteraction::max('interaction_recordid') + 1;
        $interaction->interaction_recordid = $new_recordid;

        $interaction->interaction_method = $request->interaction_method;
        $interaction->interaction_disposition = $request->interaction_disposition;
        $interaction->interaction_notes = $request->interaction_notes;
        $interaction->interaction_records_edited = $request->interaction_records_edited;
        $date_time = date("Y-m-d h:i:sa");
        $interaction->interaction_timestamp = $date_time;

        $interaction->save();

        $session = SessionData::where('session_recordid', '=', $session_recordid)->first();
        $session_original_edits = $session->session_edits;
        $session_new_edits = intval($session_original_edits) + intval($request->interaction_records_edited);
        $session->session_edits = $session_new_edits;
        $session->save();

        return redirect('session/' . $session_recordid);
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


    public function session($id)
    {
        $map = Map::find(1);
        $session = SessionData::where('session_recordid', '=', $id)->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
        $interaction_list = SessionInteraction::where('interaction_session', '=', $id)->get();
        return view('frontEnd.sessions.show', compact('session', 'map', 'disposition_list', 'method_list', 'interaction_list', 'id'));
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
        $map = Map::find(1);
        $session = SessionData::where('session_recordid', '=', $id)->first();
        $session_status_list = ['Success', 'Partial Success', 'Unable to verify', 'Out of business'];
        return view('frontEnd.sessions.edit', compact('session', 'map', 'session_status_list'));
    }

    public function session_start(Request $request)
    {
        $map = Map::find(1);
        $id = $request->get('session_id');
        $session = SessionData::where('session_recordid', '=', $id)->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $session_start_time = $request->input('session_start_time');

        $session->session_start = $session_start_time;
        $session->session_start_datetime = date("Y-m-d H:i:s");
        $session->save();
        return 'success';
    }

    public function session_end(Request $request)
    {
        $map = Map::find(1);
        $id = $request->input('session_id');
        $session = SessionData::where('session_recordid', '=', $id)->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
        $interaction_list = SessionInteraction::where('interaction_session', '=', $id)->get();
        $session_end_time = $request->input('session_end_time');
        $session->session_end = $session_end_time;
        $session->session_end_datetime = date("Y-m-d H:i:s");

        $date1 = strtotime($session->session_start_datetime);
        $date2 = strtotime(date("Y-m-d h:i:sa"));
        $diff = abs($date2 - $date1) - 1;
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
        // $duration = $years . " years,  " . $months . " months, " . $days . " days, " . $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
        $duration = $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
        $session->session_duration = $duration;
        $session->save();

        return response()->json($duration);
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
        $session = SessionData::where('session_recordid', '=', $id)->first();
        $session->session_notes = $request->session_notes;
        $session->session_verification_status = $request->session_status;
        $session->save();
        return redirect('session/' . $id);
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
    public function session_download($id)
    {
        try {
            return Excel::download(new SessionExport($id), 'Sessions.csv');
        } catch (\Throwable $th) {
            Log::error('Error in session download : ' . $th);
        }
    }
    public function interactionExport(Request $request)
    {
        try {
            try {

                return Excel::download(new InteractionExport($request), 'SessionInteraction.csv');
            } catch (\Throwable $th) {
                Log::error('Error in interaction export : ' . $th);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function addInteractionOrganization(Request $request)
    {
        try {
            $session = new SessionData;
            $new_recordid = SessionData::max('session_recordid') + 1;
            $session->session_recordid = $new_recordid;
            $user = Auth::user();
            $date_time = date("Y-m-d h:i:sa");
            $session->session_name = 'session' . $new_recordid;
            $session->session_organization = $request->organization_recordid;
            $session->session_method = $request->interaction_method;
            $session->session_disposition = $request->interaction_disposition;
            $session->session_notes = $request->interaction_notes;
            $session->organization_services = $request->organization_services ? implode(',', $request->organization_services) : '';
            $session->organization_status = $request->organization_status;
            $session->session_records_edited = $request->interaction_records_edited;

            if ($user) {
                $session->session_performed_by = $user->id;
            }

            $session->session_performed_at = Carbon::now();
            $session->session_edits = '0';
            $session->save();
            // add new interaction session
            $interaction = new SessionInteraction();
            $session_recordid = $new_recordid;
            $interaction->interaction_session = $session_recordid;

            $new_recordid = SessionInteraction::max('interaction_recordid') + 1;
            $interaction->interaction_recordid = $new_recordid;

            $interaction->interaction_method = $request->interaction_method;
            $interaction->interaction_disposition = $request->interaction_disposition;
            $interaction->interaction_notes = $request->interaction_notes;
            $interaction->organization_services = $request->organization_services ? implode(',', $request->organization_services) : '';
            $interaction->organization_status = $request->organization_status;
            $interaction->interaction_records_edited = $request->interaction_records_edited;
            $date_time = date("Y-m-d h:i:sa");
            $interaction->interaction_timestamp = $date_time;

            $interaction->save();

            $organization = Organization::where('organization_recordid', $request->organization_recordid)->first();
            $organizationStatus = OrganizationStatus::where('id', $request->organization_status)->first();

            if ($organizationStatus->status == 'Verified') {
                $organization->last_verified_at = Carbon::now();
            }
            $organization->organization_status_x = $request->organization_status;
            $organization->save();

            // $session = SessionData::where('session_recordid', '=', $session_recordid)->first();
            // $session_original_edits = $session->session_edits;
            // $session_new_edits = intval($session_original_edits) + intval($request->interaction_records_edited);
            // $session->session_edits = $session_new_edits;
            // $session->save();
            Session::flash('message', 'Interaction added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function addInteractionService(Request $request)
    {
        try {
            $session = new SessionData;
            $new_recordid = SessionData::max('session_recordid') + 1;
            $session->session_recordid = $new_recordid;
            $user = Auth::user();
            $date_time = date("Y-m-d h:i:sa");
            $session->session_name = 'session' . $new_recordid;
            $session->session_service = $request->service_recordid;
            $session->session_method = $request->interaction_method;
            $session->session_disposition = $request->interaction_disposition;
            $session->session_notes = $request->interaction_notes;
            $session->service_status = $request->service_status;
            $session->session_records_edited = $request->interaction_records_edited;

            if ($user) {
                $session->session_performed_by = $user->id;
            }

            $session->session_performed_at = Carbon::now();
            $session->session_edits = '0';
            $session->save();
            // add new interaction session
            $interaction = new SessionInteraction();
            $session_recordid = $new_recordid;
            $interaction->interaction_session = $session_recordid;

            $new_recordid = SessionInteraction::max('interaction_recordid') + 1;
            $interaction->interaction_recordid = $new_recordid;

            $interaction->interaction_method = $request->interaction_method;
            $interaction->interaction_disposition = $request->interaction_disposition;
            $interaction->interaction_notes = $request->interaction_notes;
            $interaction->interaction_records_edited = $request->interaction_records_edited;
            $date_time = date("Y-m-d h:i:sa");
            $interaction->interaction_timestamp = $date_time;

            $interaction->save();

            $service = Service::where('service_recordid', $request->service_recordid)->first();
            $service->service_status = $request->service_status;
            $service->save();

            // $session = SessionData::where('session_recordid', '=', $session_recordid)->first();
            // $session_original_edits = $session->session_edits;
            // $session_new_edits = intval($session_original_edits) + intval($request->interaction_records_edited);
            // $session->session_edits = $session_new_edits;
            // $session->save();
            Session::flash('message', 'Interaction added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
}
