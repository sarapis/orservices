<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Email;
use App\Model\Suggest;
use Illuminate\Http\Request;

class ContactFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suggests = Suggest::orderBy('created_at')->get();
        $emails = Email::orderBy('email_recordid')->get();
        return view('backEnd.contact_form.index', compact('suggests', 'emails'));
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

    public function delete_email(Request $request)
    {

        $email_recordid = $request->input('email_recordid');
        $email = Email::where('email_recordid', '=', $email_recordid)->first();
        if ($email) {
            $email->delete();
            return redirect('contact_form');
        }
    }

    public function create_email(Request $request)
    {
        $email = new Email;
        $new_recordid = Email::max('email_recordid') + 1;
        $email->email_recordid = $new_recordid;
        $email->email_info = $request->input('contact_email');
        $email->save();
        return redirect('contact_form');
    }
}
