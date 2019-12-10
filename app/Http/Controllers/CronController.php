<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutoSyncAirtable;

class CronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function cron_datasync(Request $request)
    {
      
        $auto_sync_opt = $request->airtable_enable_auto_sync;
        $auto_sync_period_days = $request->airtable_auto_sync_period;

        $auto_sync = AutoSyncAirtable::find(1);

        $auto_sync->days = $auto_sync_period_days;

        if ($auto_sync_opt = "on") {
            $auto_sync->option = "yes"; 
        }
        else {
            $auto_sync->option = "no"; 
        }

        switch ($request->input('btn_submit')) {
            case 'autosyncbtn-start':
                $auto_sync->working_status = 'yes';
            case 'autosyncbtn-stop':
                $auto_sync->working_status = 'no';
        }

        $auto_sync->save();

        return redirect('import');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
