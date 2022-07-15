<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\ServiceTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ServiceTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_tags = ServiceTag::get();

        return view('backEnd.service_tags.index', compact('service_tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.service_tags.create');
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
            'tag' => 'required'
        ]);
        try {
            DB::beginTransaction();
            ServiceTag::create([
                'tag' => $request->tag,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Service Tag Added Successfully!');
            Session::flash('tag', 'success');
            return redirect('service_tags');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('tag', 'error');
            return redirect('service_tags');
            //throw $th;
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
        $service_tag = ServiceTag::whereId($id)->first();

        return view('backEnd.service_tags.edit', compact('service_tag'));
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
            'tag' => 'required'
        ]);
        try {
            DB::beginTransaction();
            ServiceTag::whereId($id)->update([
                'tag' => $request->tag,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Service Tag Updated Successfully!');
            Session::flash('tag', 'success');
            return redirect('service_tags');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('tag', 'error');
            return redirect('service_tags');
            //throw $th;
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
        ServiceTag::whereId($id)->delete();
        Session::flash('message', 'Service Tag Deleted Successfully!');
        Session::flash('tag', 'success');
        return redirect('service_tags');
    }
}
