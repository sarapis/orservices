<?php

namespace App\Exports;

use App\Model\Analytic;
use App\Model\Organization;
use App\Model\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class AnalyticExport implements FromView
{
    public function __construct($year, $user_id)
    {
        $this->year = $year;
        $this->user_id = $user_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $additional_analytics = [];
        $names = ['Organizations Added', 'Organizations Removed', 'Organizations Modified', 'Services Added', 'Services Removed', 'Services Modified'];
        if ($this->year) {
            $year = $this->year;
        } else {
            $year = date('Y');
        }
        $user_id = null;
        if ($this->user_id && $this->user_id != 'all') {
            $user_id = $this->user_id;
        }
        foreach ($names as $key => $value) {
            $tempMonth = [];
            for ($i = 1; $i < 13; $i++) {
                $tempMonth[] = $i;
                // if ($key == 0) {
                //     $analytics_data = Analytic::whereMonth('created_at', $i)->whereYear('created_at', $year)->select(DB::raw('count(search_results) as search_count'))->first();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $analytics_data->search_count ? $analytics_data->search_count : 0;
                // } else if ($key == 1) {
                //     // $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                //     $organizations_count = Organization::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                // } else if ($key == 2) {
                //     $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                // } else if ($key == 3) {
                //     $organizations_delete_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'deleted')->where('auditable_type', 'LIKE', '%Organization%')->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_delete_count;
                // } else if ($key == 4) {
                //     $organizations_update_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'updated')->where('auditable_type', 'LIKE', '%Organization%')->select('auditable_id')->distinct()->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_update_count;
                // } else if ($key == 5) {
                //     $services_count = Service::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_count;
                // } else if ($key == 6) {
                //     $services_count = Service::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_count;
                // } else if ($key == 7) {
                //     $services_delete_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'deleted')->where('auditable_type', 'LIKE', '%Service%')->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_delete_count;
                // } else if ($key == 8) {
                //     $services_update_count = Audit::whereMonth('created_at', $i)->whereYear('created_at', $year)->where('event', 'updated')->where('auditable_type', 'LIKE', '%Service%')->select('auditable_id')->distinct()->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $services_update_count;
                // }
                // if ($key == 0) {
                //     $analytics_data = Analytic::whereMonth('created_at', $i)->whereYear('created_at', $year)->select(DB::raw('count(search_results) as search_count'))->first();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $analytics_data->search_count ? $analytics_data->search_count : 0;
                //     // } else if ($key == 1) {
                //     //     // $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                //     //     $organizations_count = Organization::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                //     //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
                // } else
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
        }
        return view('exports.analytics', [
            'additional_analytics' => $additional_analytics,
        ]);
    }
}
