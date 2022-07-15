<?php

namespace App\Http\Controllers\backEnd;

use App\Exports\AnalyticExport;
use App\Http\Controllers\Controller;
use App\Model\Analytic;
use App\Model\Organization;
use App\Model\Page;
use App\Model\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;

class AnalyticsController extends Controller
{
    protected function validator(Request $request, $id = '')
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $page = Page::findOrFail(4);
        $analytics = Analytic::all();

        $additional_analytics = [];
        $names = ['Searches', 'Total Organization', 'Organizations Added', 'Organizations Removed', 'Organizations Modified', 'Services', 'Services Added', 'Services Removed', 'Services Modified'];
        if ($request->has('year')) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }
        foreach ($names as $key => $value) {
            $tempMonth = [];
            for ($i = 1; $i < 13; $i++) {
                $tempMonth[] = $i;
                if ($key == 0) {
                    $analytics_data = Analytic::whereMonth('created_at', $i)->whereYear('created_at', $year)->select(DB::raw('count(search_results) as search_count'))->first();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $analytics_data->search_count ? $analytics_data->search_count : 0;
                } else if ($key == 1) {
                    // $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                    $organizations_count = Organization::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                } else if ($key == 2) {
                    $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                } else if ($key == 3) {
                    $organizations_delete_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'deleted')->where('auditable_type', 'LIKE', '%Organization%')->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_delete_count;
                } else if ($key == 4) {
                    $organizations_update_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'updated')->where('auditable_type', 'LIKE', '%Organization%')->select('auditable_id')->distinct()->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_update_count;
                } else if ($key == 5) {
                    $services_count = Service::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_count;
                } else if ($key == 6) {
                    $services_count = Service::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_count;
                } else if ($key == 7) {
                    $services_delete_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'deleted')->where('auditable_type', 'LIKE', '%Service%')->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_delete_count;
                } else if ($key == 8) {
                    $services_update_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'updated')->where('auditable_type', 'LIKE', '%Service%')->select('auditable_id')->distinct()->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_update_count;
                }
            }
            $additional_analytics[$key]['name'] = $value;
        }
        return view('backEnd.pages.analytics', compact('page', 'analytics', 'additional_analytics', 'year'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backEnd.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Page::create($request->all());

        Session::flash('message', 'Page added!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {

        $page = Page::findOrFail($id);
        $page->update($request->all());

        Session::flash('message', 'Page updated!');
        Session::flash('status', 'success');

        return redirect('messagesSetting');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        $page->delete();

        Session::flash('message', 'Page deleted!');
        Session::flash('status', 'success');

        return redirect('pages');
    }
    public function download_analytic_csv(Request $request, $year)
    {
        try {
            return Excel::download(new AnalyticExport($year), 'additional_analytics.csv');
        } catch (\Throwable $th) {
            return redirect('analytics');
        }
    }
}
