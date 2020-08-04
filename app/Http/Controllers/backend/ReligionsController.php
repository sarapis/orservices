<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReligionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $religions = Religion::get();
        return View('backEnd.religions.index', compact('religions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('backEnd.religions.create');
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
            'name' => 'required',
            'type' => 'required',
            // 'organizations' => 'required',
        ]);
        try {
            DB::beginTransaction();
            Religion::create([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'parent' => $request->get('parent'),
                'organizations' => $request->get('organizations'),
                'icon' => $request->get('icon'),
                'notes' => $request->get('notes'),
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->to('religions')->with('success', 'Religion created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('religions')->with('error', $th->getMessage());
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
        try {
            $religion = Religion::whereId($id)->first();
            return View('backEnd.religions.edit', compact('religion'));
        } catch (\Throwable $th) {

            return redirect()->to('religions')->with('error', $th->getMessage());
        }
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

        try {
            DB::beginTransaction();
            Religion::whereId($id)->update([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'parent' => $request->get('parent'),
                'organizations' => $request->get('organizations'),
                'icon' => $request->get('icon'),
                'notes' => $request->get('notes'),
            ]);
            DB::commit();
            return redirect()->to('religions')->with('success', 'Religion updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('religions')->with('error', $th->getMessage());
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
            DB::beginTransaction();
            Religion::whereId($id)->delete();
            DB::commit();
            return redirect()->to('religions')->with('success', 'Religion deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('religions')->with('error', $th->getMessage());
        }
    }
}
