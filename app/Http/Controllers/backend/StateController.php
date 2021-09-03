<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::cursor();
        return view('backEnd.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.states.create');
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
            'state' => 'required|unique:states,state'
        ]);
        try {
            State::create([
                'state' => $request->state
            ]);
            DB::commit();
            return redirect()->to('states')->with('success', 'State created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('states')->with('error', $th->getMessage());
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
        $state = State::whereId($id)->first();
        return view('backEnd.states.edit', compact('state'));
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
            'state' => 'required|unique:states,state'
        ]);
        try {
            State::whereId($id)->update([
                'state' => $request->state
            ]);
            DB::commit();
            return redirect()->to('states')->with('success', 'State updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('states')->with('error', $th->getMessage());
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
            State::whereId($id)->delete();

            return redirect()->to('states')->with('success', 'State Deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('states')->with('error', $th->getMessage());
        }
    }
    public function add_state()
    {
        try {
            $addresses = Address::cursor();
            foreach ($addresses as $key => $value) {
                if ($value->address_state_province) {
                    $state = State::where('state', rtrim(ltrim($value->address_state_province)))->exists();
                    if (!$state)
                        State::create(['state' => rtrim(ltrim($value->address_state_province))]);
                }
            }
            return redirect('/states');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
