<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\CodeLedger;
use App\Model\CodeSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CodeSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $codeSystems = CodeSystem::cursor();
        return view('backEnd.code_systems.index', compact('codeSystems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.code_systems.create');
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
            CodeSystem::create([
                'name' => $request->name,
                'url' => $request->url,
                'oid' => $request->oid,
                'versions' => $request->versions,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            return redirect()->to('code_systems')->with('success', 'Code System created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('code_systems')->with('error', $th->getMessage());
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
        $codeSystem = CodeSystem::whereId($id)->first();
        return view('backEnd.code_systems.edit', compact('codeSystem'));
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
            CodeSystem::whereId($id)->update([
                'name' => $request->name,
                'url' => $request->url,
                'oid' => $request->oid,
                'versions' => $request->versions,
            ]);
            DB::commit();
            return redirect()->to('code_systems')->with('success', 'Code System updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('code_systems')->with('error', $th->getMessage());
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
            CodeSystem::whereId($id)->delete();

            return redirect()->to('code_systems')->with('success', 'Code System Deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('code_systems')->with('error', $th->getMessage());
        }
    }
    public function pullCodeSystem()
    {
        $codes = Code::cursor();
        foreach ($codes as $key => $value) {
            if ($value->code_system) {
                $codeSystem = CodeSystem::firstOrCreate(
                    ['name' => $value->code_system]
                );
                $value->code_system = $codeSystem->id;
                $value->save();
            }
        }
        return "done";
    }
    public function pullCodeSystemLedger()
    {
        $CodeLedger = CodeLedger::cursor();
        foreach ($CodeLedger as $key => $value) {
            if ($value->code_type) {
                $codeSystem = CodeSystem::firstOrCreate(
                    ['name' => $value->code_type]
                );
                $value->code_type = $codeSystem->id;
                $value->save();
            }
        }
        return "done";
    }
}
