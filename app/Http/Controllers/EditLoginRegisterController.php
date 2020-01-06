<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Page;
use App\Layout;
use App\Airtables;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;


class EditLoginRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function validator(Request $request,$id='')
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',            
            'body' => 'required',
        ]);
    }

    public function index()
    {
        $page = Page::findOrFail(5);
        $layout = Layout::find(1);
        return view('backEnd.pages.edit_login_register', compact('page', 'layout'));
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
        $layout = Layout::find(1);
        $layout->login_content=$request->login_content;
        $layout->register_content = $request->register_content;        
        $layout->save();

        if ($this->validator($request,Sentinel::getUser()->id)->fails()) {
            
            return redirect()->back()
                    ->withErrors($this->validator($request))
                    ->withInput();
        }
        $page = Page::findOrFail($id);        
        $page->update($request->all());     

        Session::flash('message', 'Page updated!');
        Session::flash('status', 'success');

        return redirect('login_register_edit');
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
