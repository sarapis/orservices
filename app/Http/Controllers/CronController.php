<?php

namespace App\Http\Controllers;

use App\Model\AutoSyncAirtable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CronController extends Controller
{
    public function cron_datasync(Request $request)
    {
        try {
            $auto_sync_opt = $request->airtable_enable_auto_sync;
            $auto_sync_period_days = $request->airtable_auto_sync_period;

            $auto_sync = AutoSyncAirtable::find(1);

            $auto_sync->days = $auto_sync_period_days;

            if ($auto_sync_opt == "checked") {
                $auto_sync->option = "yes";
            } else {
                $auto_sync->option = "no";
            }

            // switch ($request->input('btn_submit')) {
            //     case 'autosyncbtn-start':
            //         $auto_sync->working_status = 'yes';
            //     case 'autosyncbtn-stop':
            //         $auto_sync->working_status = 'no';
            // }


            $auto_sync->save();
            Session::flash('message', 'saved successfully!');
            Session::flash('status', 'success');
            return redirect('import');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
