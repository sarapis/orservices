<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\PhoneType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhoneTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $phone_types = PhoneType::orderBy('order')->get();

        return view('backEnd.phone_types.index', compact('phone_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.phone_types.create');
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
            'type' => 'required',
            'order' => 'required|unique:phone_types,order'
        ]);
        try {
            DB::beginTransaction();
            PhoneType::create([
                'type' => $request->type,
                'order' => $request->order
            ]);
            DB::commit();
            return redirect('phone_types')->with('success', 'Phone type created successfully');
        } catch (\Throwable $th) {
            return redirect()->to('phone_types')->with('error', $th->getMessage());
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
        $phone_type = PhoneType::whereId($id)->first();
        return view('backEnd.phone_types.edit', compact('phone_type'));
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
            'type' => 'required',
            'order' => 'required'
        ]);
        try {
            DB::beginTransaction();
            PhoneType::whereId($id)->update([
                'type' => $request->type,
                'order' => $request->order
            ]);
            DB::commit();
            return redirect('phone_types')->with('success', 'Phone type updated successfully');
        } catch (\Throwable $th) {
            return redirect()->to('phone_types')->with('error', $th->getMessage());
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
            PhoneType::whereId($id)->delete();
            DB::commit();
            return redirect('phone_types')->with('success', 'Phone type deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->to('phone_types')->with('error', $th->getMessage());
        }
    }
}
