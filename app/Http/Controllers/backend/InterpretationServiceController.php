<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\InterpretationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterpretationServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interpretation_services = InterpretationService::cursor();
        return view('backEnd.interpretation_service.index', compact('interpretation_services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.interpretation_service.create');
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
            'name' => 'required'
        ]);
        try {
            InterpretationService::create([
                'name' => $request->name
            ]);
            DB::commit();
            return redirect()->to('service_interpretations')->with('success', 'Interpretation Service Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('service_interpretations')->with('error', $th->getMessage());
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
        $interpretation_service = InterpretationService::whereId($id)->first();
        return view('backEnd.interpretation_service.edit', compact('interpretation_service'));
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
            'name' => 'required'
        ]);
        try {
            InterpretationService::whereId($id)->update([
                'name' => $request->name
            ]);
            DB::commit();
            return redirect()->to('service_interpretations')->with('success', 'Interpretation service updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('service_interpretations')->with('error', $th->getMessage());
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
            InterpretationService::whereId($id)->delete();

            return redirect()->to('service_interpretations')->with('success', 'Interpretation service Deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('service_interpretations')->with('error', $th->getMessage());
        }
    }
}
