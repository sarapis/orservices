<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Service;
use App\Model\ServiceAttribute;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ServiceAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $service_attributes = ServiceTaxonomy::get();

            if (!$request->ajax()) {
                return view('backEnd.service_attributes.index', compact('service_attributes'));
            }
            $service_attributes = ServiceTaxonomy::select('*');
            return DataTables::of($service_attributes)
                ->editColumn('taxonomy_recordid', function ($row) {
                    return $row->taxonomy ? $row->taxonomy->taxonomy_name : '';
                })
                ->editColumn('service_recordid', function ($row) {
                    return $row->service ? $row->service->service_name : '';
                })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("service_attributes.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'service_attributes';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in service attribute index : ' . $th);
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
        $taxonomy = Taxonomy::whereNotNull('taxonomy_name')->pluck('taxonomy_name', 'taxonomy_recordid');
        return view('backEnd.service_attributes.create', compact('services', 'taxonomy'));
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
            'service_recordid' => 'required',
            'taxonomy_recordid' => 'required',
        ]);
        try {
            ServiceTaxonomy::create([
                'taxonomy_recordid' => $request->taxonomy_recordid,
                'service_recordid' => $request->service_recordid
            ]);
            Session::flash('message', 'Success! Attribute is created successfully.');
            Session::flash('status', 'success');
            return redirect('/service_attributes');
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
        $service_attribute = ServiceTaxonomy::whereId($id)->first();
        $services = Service::whereNotNull('service_name')->pluck('service_name', 'service_recordid');
        $taxonomy = Taxonomy::whereNotNull('taxonomy_name')->pluck('taxonomy_name', 'taxonomy_recordid');
        return view('backEnd.service_attributes.edit', compact('service_attribute', 'services', 'taxonomy'));
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
            'service_recordid' => 'required',
            'taxonomy_recordid' => 'required',
        ]);
        try {
            ServiceTaxonomy::whereId($id)->update([
                'taxonomy_recordid' => $request->taxonomy_recordid,
                'service_recordid' => $request->service_recordid
            ]);
            Session::flash('message', 'Success! Attribute is updated successfully.');
            Session::flash('status', 'success');
            return redirect('/service_attributes');
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
        try {
            $ServiceAttribute = ServiceTaxonomy::findOrFail($id);
            $ServiceAttribute->delete();

            Session::flash('message', 'Success! Attribute is deleted successfully.');
            Session::flash('status', 'success');

            return redirect('service_attributes');
        } catch (\Throwable $th) {
            Log::error('Error in destroy service attribute : ' . $th);
        }
    }
}
