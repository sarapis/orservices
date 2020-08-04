<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\facilityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacilityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $FacilityTypes = facilityType::get();

        return view('backEnd.facility_type.index', compact('FacilityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.facility_type.create');
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
            'facility_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            facilityType::create([
                'facility_type' => $request->get('facility_type'),
                'notes' => $request->get('notes'),
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            return redirect()->to('FacilityTypes')->with('success', 'Facility type added successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('FacilityTypes')->with('error', $th->getMessage());
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
        $FacilityType = facilityType::whereId($id)->first();

        return view('backEnd.facility_type.edit', compact('FacilityType'));
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
            'facility_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            facilityType::whereId($id)->update([
                'facility_type' => $request->get('facility_type'),
                'notes' => $request->get('notes'),
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            return redirect()->to('FacilityTypes')->with('success', 'Facility type updated successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('FacilityTypes')->with('error', $th->getMessage());
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
            DB::beginTransaction();

            facilityType::whereId($id)->update([
                'deleted_by' => Auth::id(),
            ]);
            DB::commit();

            facilityType::whereId($id)->delete();

            DB::commit();
            return redirect()->to('FacilityTypes')->with('success', 'Facility type deleted successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('FacilityTypes.index')->with('error', $th->getMessage());
        }
    }
}
