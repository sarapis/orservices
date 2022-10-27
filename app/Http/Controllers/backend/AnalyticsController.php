<?php

namespace App\Http\Controllers\backEnd;

use App\Exports\AnalyticExport;
use App\Exports\SearchAnalyticsExport;
use App\Http\Controllers\Controller;
use App\Model\Analytic;
use App\Model\Organization;
use App\Model\Page;
use App\Model\Service;
use App\User;
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
        $users = User::get();

        $additional_analytics = [];
        $names = ['Organizations Added', 'Organizations Removed', 'Organizations Modified', 'Services Added', 'Services Removed', 'Services Modified'];
        $searches_analytics = [];
        $searchesData = ['Searches'];
        // $names = ['Searches', 'Total Organization', 'Organizations Added', 'Organizations Removed', 'Organizations Modified', 'Services', 'Services Added', 'Services Removed', 'Services Modified'];
        if ($request->has('year')) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }
        if ($request->has('search_year')) {
            $search_year = $request->search_year;
        } else {
            $search_year = date('Y');
        }
        $user_id = null;
        if ($request->has('user_id')) {
            $user_id = $request->user_id;
        }
        foreach ($names as $key => $value) {
            $tempMonth = [];
            for ($i = 1; $i < 13; $i++) {
                $tempMonth[] = $i;
                if ($key == 0) {
                    $analytics_data = Analytic::whereMonth('created_at', $i)->whereYear('created_at', $search_year)->select(DB::raw('count(search_results) as search_count'))->first();
                    $searches_analytics[0][date('M', mktime(0, 0, 0, $i, 10))] = $analytics_data->search_count ? $analytics_data->search_count : 0;
                    // } else if ($key == 1) {
                    //     // $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                    //     $organizations_count = Organization::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                    //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                }
                if ($key == 0) {
                    $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year);
                    if ($user_id) {
                        $organizations_count = $organizations_count->where('created_by', $user_id);
                    }
                    $organizations_count = $organizations_count->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                } else if ($key == 1) {
                    $organizations_delete_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'deleted')->where('auditable_type', 'LIKE', '%Organization%');

                    if ($user_id) {
                        $organizations_delete_count = $organizations_delete_count->where('user_id', $user_id);
                    }
                    $organizations_delete_count = $organizations_delete_count->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_delete_count;
                } else if ($key == 2) {
                    $organizations_update_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'updated')->where('auditable_type', 'LIKE', '%Organization%');
                    if ($user_id) {
                        $organizations_update_count = $organizations_update_count->where('user_id', $user_id);
                    }
                    $organizations_update_count = $organizations_update_count->select('auditable_id')->distinct()->count();

                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_update_count;
                    // } else if ($key == 5) {
                    //     $services_count = Service::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                    //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_count;
                } else if ($key == 3) {
                    $services_count = Service::whereMonth('created_at', $i)->whereYear('created_at', $year);
                    if ($user_id) {
                        $services_count = $services_count->where('created_by', $user_id);
                    }
                    $services_count = $services_count->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_count;
                } else if ($key == 4) {
                    $services_delete_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'deleted')->where('auditable_type', 'LIKE', '%Service%');
                    if ($user_id) {
                        $services_delete_count = $services_delete_count->where('user_id', $user_id);
                    }
                    $services_delete_count = $services_delete_count->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_delete_count;
                } else if ($key == 5) {
                    $services_update_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'updated')->where('auditable_type', 'LIKE', '%Service%');
                    if ($user_id) {
                        $services_update_count = $services_update_count->where('user_id', $user_id);
                    }
                    $services_update_count = $services_update_count->select('auditable_id')->distinct()->count();
                    $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_update_count;
                }
            }
            $additional_analytics[$key]['name'] = $value;
            $searches_analytics[0]['name'] = 'Searches';
        }
        return view('backEnd.pages.analytics', compact('page', 'analytics', 'additional_analytics', 'year', 'users', 'user_id', 'searches_analytics', 'search_year'));
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
    public function download_analytic_csv(Request $request, $year, $user_id)
    {
        try {
            return Excel::download(new AnalyticExport($year, $user_id), 'additional_analytics.csv');
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('analytics');
        }
    }
    public function download_search_analytic_csv(Request $request, $year)
    {
        try {
            return Excel::download(new SearchAnalyticsExport($year), 'search_analytics.csv');
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('analytics');
        }
    }
}
