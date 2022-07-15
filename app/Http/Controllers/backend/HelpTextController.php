<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\Helptext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HelpTextController extends Controller
{
    public function helptexts()
    {
        $helptext = Helptext::first();
        $codes = Code::pluck('category', 'id')->unique();
        return view('backEnd.help_text.help_text', compact('helptext', 'codes'));
    }
    public function save_helptexts(Request $request)
    {
        try {
            $helptext =  Helptext::first();
            if ($helptext) {
                $helptext->service_classification = $request->service_classification;
                $helptext->service_conditions = $request->service_conditions;
                $helptext->service_goals = $request->service_goals;
                $helptext->service_activities = $request->service_activities;
                $helptext->code_category = $request->code_category;
                $helptext->sdoh_code_helptext = $request->sdoh_code_helptext;
                $helptext->registration_message = $request->registration_message;
                $helptext->save();
            } else {
                Helptext::create([
                    'service_classification' => $request->service_classification,
                    'service_conditions' => $request->service_conditions,
                    'service_goals' => $request->service_goals,
                    'service_activities' => $request->service_activities,
                    'code_category' => $request->code_category,
                    'sdoh_code_helptext' => $request->sdoh_code_helptext,
                    'registration_message' => $request->registration_message,
                ]);
            }
            Session::flash('message', 'Success! Help text store successfully.');
            Session::flash('status', 'success');
            return redirect('helptexts');
        } catch (\Throwable $th) {
            Session::flash('message', 'Error! ' . $th->getMessage());
            Session::flash('status', 'error');
            return redirect('helptexts');
        }
    }
}
