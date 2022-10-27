<?php

namespace App\Exports;

use App\Model\Analytic;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SearchAnalyticsExport implements FromView
{
    public function __construct($year)
    {
        $this->year = $year;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $additional_analytics = [];
        $names = ['Searches'];
        if ($this->year) {
            $year = $this->year;
        } else {
            $year = date('Y');
        }
        foreach ($names as $key => $value) {
            $tempMonth = [];
            for ($i = 1; $i < 13; $i++) {
                $tempMonth[] = $i;
                $analytics_data = Analytic::whereMonth('created_at', $i)->whereYear('created_at', $year)->select(DB::raw('count(search_results) as search_count'))->first();
                $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $analytics_data->search_count ? $analytics_data->search_count : 0;
                // } else if ($key == 1) {
                //     // $organizations_count = Organization::whereMonth('created_at', $i)->whereYear('created_at', $year)->count();
                //     $organizations_count = Organization::whereRaw('MONTH(created_at) in (' . implode(',', $tempMonth) . ')')->whereYear('created_at', $year)->count();
                //     $additional_analytics[$key][date('M', mktime(0, 0, 0, $i, 10))] = $organizations_count;
            }
            $additional_analytics[$key]['name'] = $value;
        }
        return view('exports.analytics', [
            'additional_analytics' => $additional_analytics,
        ]);
    }
}
