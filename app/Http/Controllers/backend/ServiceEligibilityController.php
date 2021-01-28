<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\ServiceEligibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceEligibilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_eligibilities = ServiceEligibility::get();
        return view('backEnd.service_eligibility.index', compact('service_eligibilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.service_eligibility.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            ServiceEligibility::create([
                'term' => $request->term,
                'created_by' => Auth::id()
            ]);
            Session::flash('message', 'Service eligibility created successfully');
            Session::flash('status', 'success');
            return redirect('service_eligibilities');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_eligibilities');
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
        $service_eligibility = ServiceEligibility::whereId($id)->first();
        return view('backEnd.service_eligibility.edit', compact('service_eligibility'));
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
        try {
            ServiceEligibility::whereId($id)->update([
                'term' => $request->term,
                'updated_by' => Auth::id()
            ]);
            Session::flash('message', 'Service eligibility updated successfully');
            Session::flash('status', 'success');
            return redirect('service_eligibilities');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_eligibilities');
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
        try {

            $service_eligibility = ServiceEligibility::whereid($id)->first();
            // Detail::where('detail_type', $service_eligibility->type)->update(['detail_type' => '']);

            ServiceEligibility::whereid($id)->delete();

            Session::flash('message', 'Service eligibility deleted successfully');
            Session::flash('status', 'success');
            return redirect('service_eligibilities');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_eligibilities');
        }
    }
}
