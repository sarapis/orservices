<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\CodeLedger;
use App\Model\Helptext;
use App\Model\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function add_code_category_ids(Request $request)
    {
        try {
            $codeIds = $request->ids ? $request->ids : [];
            $service_codes = $request->selected_codes ? $request->selected_codes : [];

            $service_recordid = $request->service_recordid;
            $category_names = Code::whereIn('id', $codeIds)->pluck('category');
            $codes = Code::whereIn('category', $category_names)->whereIn('resource', ['Condition', 'Goal', 'Procedure'])->orderBy('category')->orderBy('resource')->orderBy('grouping')->get()->groupBy(['category', 'resource', 'grouping']);
            $service_codes_old = [];
            $procedure_grouping = [];
            if ($service_recordid && count($codeIds) > 0) {
                $service = Service::where('service_recordid', $service_recordid)->first();
                $service->code_category_ids = implode(',', $request->ids);
                $service->save();
                if ($service) {
                    // $service_codes_old = $service->codes()->select(DB::raw('CONCAT(rating, "_", SDOH_code) as code_id'))->pluck('code_id')->toArray();
                    $procedure_grouping = $service->procedure_grouping ? unserialize($service->procedure_grouping) : [];

                    // foreach ($service_codes_old as $key => $value) {
                    //     if ($service_codes) {
                    //         if (!in_array($value, $service_codes))
                    //             $service_codes[] = $value;
                    //     }
                    // }
                }
            }
            $help_text = Helptext::first();

            // $conditions = Code::whereIn('category', $category_names)->where('resource', 'Condition')->get()->groupBy('category');
            // $goals = Code::whereIn('category', $category_names)->where('resource', 'Goal')->get()->groupBy('category');
            // $activities = Code::whereIn('category', $category_names)->where('resource', 'Procedure')->get()->groupBy('category');

            return view('frontEnd.services.code_category_resources', compact('codes', 'service_codes', 'help_text', 'service_recordid', 'procedure_grouping'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
