<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Detail;
use App\Model\DetailType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DetailTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detail_types = DetailType::get();

        return view('backEnd.detail_types.index', compact('detail_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.detail_types.create');
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
            'order' => 'required|unique:detail_types,order'
        ]);
        try {
            DetailType::create([
                'type' => $request->type,
                'order' => $request->order,
                'created_by' => Auth::id()
            ]);
            Session::flash('message', 'Detail type created successfully');
            Session::flash('status', 'success');
            return redirect('detail_types');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('detail_types');
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
        $detail_type = DetailType::whereId($id)->first();
        return view('backEnd.detail_types.edit', compact('detail_type'));
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
            DetailType::whereId($id)->update([
                'type' => $request->type,
                'order' => $request->order,
                'updated_by' => Auth::id()
            ]);
            Session::flash('message', 'Detail type updated successfully');
            Session::flash('status', 'success');
            return redirect('detail_types');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('detail_types');
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

            $detailType = DetailType::whereid($id)->first();
            Detail::where('detail_type', $detailType->type)->update(['detail_type' => '']);

            DetailType::whereid($id)->delete();

            Session::flash('message', 'Detail type deleted successfully');
            Session::flash('status', 'success');
            return redirect('detail_types');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('detail_types');
        }
    }
}
