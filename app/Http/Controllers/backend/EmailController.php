<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\EmailTemplate;
use App\Model\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layout = Layout::find(1);
        $createMail = EmailTemplate::whereId(1)->first();
        $activationMail = EmailTemplate::whereId(2)->first();
        return view('backEnd.emails.system_emails', compact('layout', 'activationMail', 'createMail'));
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
        try {
            $createMail = EmailTemplate::whereId(1)->first();
            if ($createMail) {
                $createMail->status = $request->create_status == 'checked' ? 1 : '';
                $createMail->subject = $request->create_subject;
                $createMail->body = $request->create_body;
                $createMail->created_by = Auth::id();
                $createMail->save();
            } else {
                EmailTemplate::create([
                    'id' => 1,
                    'status' => $request->create_status == 'checked' ? 1 : '',
                    'subject' => $request->create_subject,
                    'body'  => $request->create_body,
                    'created_by' => Auth::id()
                ]);
            }
            $activationMail = EmailTemplate::whereId(2)->first();
            if ($activationMail) {
                $activationMail->status = $request->activation_status == 'checked' ? 1 : '';
                $activationMail->subject = $request->activation_subject;
                $activationMail->body = $request->activation_body;
                $activationMail->created_by = Auth::id();
                $activationMail->save();
            } else {
                EmailTemplate::create([
                    'id' => 2,
                    'status' => $request->activation_status == 'checked' ? 1 : '',
                    'subject' => $request->activation_subject,
                    'body'  => $request->activation_body,
                    'created_by' => Auth::id()
                ]);
            }
            Session::flash('message', 'Email data saved!');
            Session::flash('status', 'success');

            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');

            return redirect()->back();
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
