<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\InteractionMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InteractionMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interaction_methods = InteractionMethod::get();
        return view('backEnd.interaction_method.index', compact('interaction_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.interaction_method.create');
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
        ]);
        try {
            InteractionMethod::create([
                'name' => $request->name,
                'created_by' => Auth::id()
            ]);
            Session::flash('message', 'Interaction Method created successfully');
            Session::flash('status', 'success');
            return redirect('interaction_methods');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('interaction_methods');
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
        $interaction_method = InteractionMethod::whereId($id)->first();
        return view('backEnd.interaction_method.edit', compact('interaction_method'));
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
            'name' => 'required',
        ]);
        try {
            InteractionMethod::whereId($id)->update([
                'name' => $request->name,
                'updated_by' => Auth::id()
            ]);
            Session::flash('message', 'Interaction Method updated successfully');
            Session::flash('status', 'success');
            return redirect('interaction_methods');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('interaction_methods');
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
            InteractionMethod::whereid($id)->delete();

            Session::flash('message', 'Interaction Method deleted successfully');
            Session::flash('status', 'success');
            return redirect('interaction_methods');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('interaction_methods');
        }
    }
}
