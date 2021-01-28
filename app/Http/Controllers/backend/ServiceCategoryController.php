<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_categories = ServiceCategory::get();
        return view('backEnd.service_category.index', compact('service_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.service_category.create');
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
            ServiceCategory::create([
                'term' => $request->term,
                'created_by' => Auth::id()
            ]);
            Session::flash('message', 'Service category created successfully');
            Session::flash('status', 'success');
            return redirect('service_categories');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_categories');
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
        $service_category = ServiceCategory::whereId($id)->first();
        return view('backEnd.service_category.edit', compact('service_category'));
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
            ServiceCategory::whereId($id)->update([
                'term' => $request->term,
                'updated_by' => Auth::id()
            ]);
            Session::flash('message', 'Service Category updated successfully');
            Session::flash('status', 'success');
            return redirect('service_categories');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_categories');
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

            $service_category = ServiceCategory::whereid($id)->first();
            // Detail::where('detail_type', $service_category->type)->update(['detail_type' => '']);

            ServiceCategory::whereid($id)->delete();

            Session::flash('message', 'Service category deleted successfully');
            Session::flash('status', 'success');
            return redirect('service_categories');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_categories');
        }
    }
}
