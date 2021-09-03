<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Helptext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HelpTextController extends Controller
{
    public function helptexts()
    {
        $helptext = Helptext::first();
        return view('backEnd.help_text.help_text', compact('helptext'));
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
                $helptext->save();
            } else {
                Helptext::create([
                    'service_classification' => $request->service_classification,
                    'service_conditions' => $request->service_conditions,
                    'service_goals' => $request->service_goals,
                    'service_activities' => $request->service_activities,
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
