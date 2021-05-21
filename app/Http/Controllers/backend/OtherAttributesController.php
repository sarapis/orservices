<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\OtherAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OtherAttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $other_attributes = OtherAttribute::get();

            if (!$request->ajax()) {
                return view('backEnd.other_attributes.index', compact('other_attributes'));
            }
            $other_attributes = OtherAttribute::select('*');
            return DataTables::of($other_attributes)
                // ->addColumn('method', function($row){
                //     return 'Email';
                // })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("other_attributes.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'other_attributes';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in Other attribute index : ' . $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.other_attributes.create');
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
            'link_id' => 'required',
            'link_type' => 'required',
        ]);
        try {
            OtherAttribute::create([
                'taxonomy_term_id' => $request->taxonomy_term_id,
                'link_id' => $request->link_id,
                'link_type' => $request->link_type
            ]);
            Session::flash('message', 'Success! Attribute is created successfully.');
            Session::flash('status', 'success');
            return redirect('/other_attributes');
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
        $other_attributes = OtherAttribute::whereId($id)->first();
        return view('backEnd.other_attributes.edit', compact('other_attributes'));
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
            'link_id' => 'required',
            'link_type' => 'required',
        ]);
        try {
            OtherAttribute::whereId($id)->update([
                'taxonomy_term_id' => $request->taxonomy_term_id,
                'link_id' => $request->link_id,
                'link_type' => $request->link_type
            ]);
            Session::flash('message', 'Success! Attribute is updated successfully.');
            Session::flash('status', 'success');
            return redirect('/other_attributes');
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
        $OtherAttribute = OtherAttribute::findOrFail($id);
        $OtherAttribute->delete();

        Session::flash('message', 'Success! Attribute is deleted successfully.');
        Session::flash('status', 'success');

        return redirect()->route('other_attributes.index');
    }
}
