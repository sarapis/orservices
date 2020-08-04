<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\contactType;
use App\Model\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ContactTypes = contactType::get();
        $layout = Layout::first();

        return view('backEnd.contactType.index', compact('ContactTypes', 'layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.contactType.create');
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
            'contact_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            contactType::create([
                'contact_type' => $request->get('contact_type'),
                'notes' => $request->get('notes'),
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            return redirect()->to('ContactTypes')->with('success', 'Contact type added successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('ContactTypes')->with('error', $th->getMessage());
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
        $ContactType = contactType::whereId($id)->first();

        return view('backEnd.contactType.edit', compact('ContactType'));
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
            'contact_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            contactType::whereId($id)->update([
                'contact_type' => $request->get('contact_type'),
                'notes' => $request->get('notes'),
            ]);
            DB::commit();
            return redirect()->to('ContactTypes')->with('success', 'Contact type updated successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('ContactTypes.index')->with('error', $th->getMessage());
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

            contactType::whereId($id)->delete();

            DB::commit();
            return redirect()->to('ContactTypes')->with('success', 'Contact type deleted successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->to('ContactTypes.index')->with('error', $th->getMessage());
        }
    }
}
