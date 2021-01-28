<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Detail;
use App\Model\DetailType;
use App\Model\Location;
use App\Model\Organization;
use App\Model\Service;
use App\Model\XDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class XDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {

            $xdetails = Detail::get();
            $detail_types = DetailType::pluck('type', 'type');

            if (!$request->ajax()) {
                return view('backEnd.xdetails.index', compact('xdetails', 'detail_types'));
            }
            $xdetails = Detail::select('*');
            return DataTables::of($xdetails)
                ->editColumn('detail_services', function ($row) {
                    $service_name = '';
                    if ($row->detail_services) {
                        $serviceIds = explode(',', $row->detail_services);

                        $services = Service::whereIn('service_recordid', $serviceIds)->pluck('service_name')->toArray();
                        $service_name .= implode(',', $services);
                    }
                    return $service_name;
                })
                ->editColumn('detail_organizations', function ($row) {
                    return $row->organization ? $row->organization->organization_name : '';
                })
                ->editColumn('detail_locations', function ($row) {
                    return $row->location && count($row->location) > 0 && $row->location[0] ? $row->location[0]->location_name : '';
                })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("XDetails.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'XDetails';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');

                    if ($extraData) {
                        if (isset($extraData['detail_type']) && $extraData['detail_type'] != null) {
                            $query = $query->whereIn('detail_type', $extraData['detail_type']);
                        }
                    }
                    return $query;
                })
                ->rawColumns(['action'])
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
        $services = Service::whereNotNull('service_name')->pluck('service_name', 'service_recordid');
        $locations = Location::whereNotNull('location_name')->pluck('location_name', 'location_recordid');
        $organizations = Organization::whereNotNull('organization_name')->pluck('organization_name', 'organization_recordid');
        $detail_types = DetailType::pluck('type', 'type');
        return view('backEnd.xdetails.create', compact('organizations', 'locations', 'services', 'detail_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'detail_value' => 'required',
            'detail_type' => 'required',
        ]);
        try {
            $details = Detail::get();
            if (!empty($details)) {
                $detail_recordid = Detail::max('detail_recordid') + 1;
            } else {
                $detail_recordid = 1;
            }
            Detail::create([
                'detail_recordid' => $detail_recordid,
                'detail_value' => $request->detail_value,
                'detail_type' => $request->detail_type,
                'detail_description' => $request->detail_description,
                'detail_services' => $request->detail_services,
                'detail_organizations' => $request->detail_organizations,
                'detail_locations' => $request->detail_locations,
                // 'phone_id' => $request->phone_id,
            ]);
            Session::flash('message', 'Success! Details is created successfully.');
            Session::flash('status', 'success');
            return redirect('/XDetails');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
        }
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
        $xdetails = Detail::whereId($id)->first();
        $serviceIds = $xdetails->detail_services ? explode(',', $xdetails->detail_services) : [];
        $services = Service::whereNotNull('service_name')->pluck('service_name', 'service_recordid');
        $locations = Location::whereNotNull('location_name')->pluck('location_name', 'location_recordid');
        $organizations = Organization::whereNotNull('organization_name')->pluck('organization_name', 'organization_recordid');
        $detail_types = DetailType::pluck('type', 'type');
        return view('backEnd.xdetails.edit', compact('xdetails', 'services', 'locations', 'organizations', 'serviceIds', 'detail_types'));
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
        $this->validate($request, [
            'detail_value' => 'required',
            'detail_type' => 'required',
        ]);
        try {
            Detail::whereId($id)->update([
                'detail_value' => $request->detail_value,
                'detail_type' => $request->detail_type,
                'detail_description' => $request->detail_description,
                'detail_services' => $request->detail_services,
                'detail_organizations' => $request->detail_organizations,
                'detail_locations' => $request->detail_locations,
                // 'phone_id' => $request->phone_id,
            ]);
            Session::flash('message', 'Success! Details is updated successfully.');
            Session::flash('status', 'success');
            return redirect('/XDetails');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $XDetail = Detail::findOrFail($id);
        $XDetail->delete();

        Session::flash('message', 'Success! Details is deleted successfully.');
        Session::flash('status', 'success');

        return redirect()->route('XDetails.index');
    }
}
