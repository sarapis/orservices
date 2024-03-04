<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\AddressType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addressTypes = AddressType::cursor();
        return view('backEnd.address_types.index', compact('addressTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.address_types.create');
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
            'type' => 'required|unique:address_types,type'
        ]);
        try {
            DB::beginTransaction();
            AddressType::create([
                'type' => $request->type,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            return redirect()->to('address_types')->with('success', 'Address type created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('address_types')->with('error', $th->getMessage());
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
        $addressType = AddressType::whereId($id)->first();
        return view('backEnd.address_types.edit', compact('addressType'));
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
            'type' => 'required|unique:address_types,id,' . $id
        ]);
        try {
            AddressType::whereId($id)->update([
                'type' => $request->type
            ]);
            DB::commit();
            return redirect()->to('address_types')->with('success', 'Address type updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('address_types')->with('error', $th->getMessage());
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
            AddressType::whereId($id)->delete();
            return redirect()->to('address_types')->with('success', 'Address type Deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('address_types')->with('error', $th->getMessage());
        }
    }
}
