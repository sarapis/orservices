<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Contact;
use App\Model\Layout;
use App\Model\PoliticalParty;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class PoliticalPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layout = Layout::find(1);
        $parties = PoliticalParty::orderBy('id', 'desc')->get();
        return view('backEnd.politicalParty.index', compact('parties', 'layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $layout = Layout::find(1);
        return view('backEnd.politicalParty.create', compact('layout'));
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
            DB::beginTransaction();
            PoliticalParty::create([
                'name' => $request->name,
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            Session::flash('message', 'Party created successfully!');
            Session::flash('status', 'success');
            return redirect('parties');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('parties');
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
            $layout = Layout::find(1);
            $party = PoliticalParty::whereId($id)->first();

            return view('backEnd.politicalParty.edit', compact('party', 'layout'));
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('parties');
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
        $this->validate($request, [
            'name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            PoliticalParty::whereId($id)->update([
                'name' => $request->name,
                'updated_by' => Auth::id(),
            ]);
            DB::commit();
            Session::flash('message', 'Party updated successfully!');
            Session::flash('status', 'success');
            return redirect('parties');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('parties');
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
            PoliticalParty::whereId($id)->delete();

            $contacts = Contact::where('political_party', $id)->get();
            foreach ($contacts as $key => $value) {
                $contact = Contact::whereId($value->id)->first();
                $contact->political_party = '';
                $contact->save();
            }
            Session::flash('message', 'Party deleted successfully!');
            Session::flash('status', 'success');
            return redirect('parties');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('parties');
        }
    }
}
