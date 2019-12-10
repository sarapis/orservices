<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Layout;
use App\Page;
use Image;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;

class EditlayoutController extends Controller
{
    protected function validator(Request $request,$id='')
    {
        return Validator::make($request->all(), [
          
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $layout = Layout::find(1);

        return view('backEnd.pages.layout', compact('layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backEnd.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($this->validator($request,Sentinel::getUser()->id)->fails()) {
            
            return redirect()->back()
                    ->withErrors($this->validator($request))
                    ->withInput();
        }
        
        Layout::create($request->all());

        Session::flash('message', 'Page added!');
        Session::flash('status', 'success');

        return redirect('layout_edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        // if ($this->validator($request,Sentinel::getUser()->id)->fails()) {
            
        //     return redirect()->back()
        //             ->withErrors($this->validator($request))
        //             ->withInput();
        // }
        
        $layout = Layout::find($id);
        if($request->hasFile('logo')){
            $companylogo = $request->file('logo');
            $filename = time() . '.' . $companylogo->getClientOriginalExtension();
            Image::make($companylogo)->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();})->save( public_path('/uploads/images/' . $filename ) );
            $layout->logo=$filename;
        }
        if($request->hasFile('top_background')){
            $top_background = $request->file('top_background');
            $top_background_filename = time() . '.' . $top_background->getClientOriginalExtension();
            Image::make($top_background)->save( public_path('/uploads/images/' . $top_background_filename ) );
            $layout->top_background=$top_background_filename;
        }
        if($request->hasFile('bottom_background')){
            $bottom_background = $request->file('bottom_background');
            $bottom_background_filename = time() . '.' . $bottom_background->getClientOriginalExtension();
            Image::make($bottom_background)->save( public_path('/uploads/images/' . $bottom_background_filename ) );
            $layout->bottom_background=$bottom_background_filename;
        }
        if ($request->input('logo_active') == 'checked')
        {
            $layout->logo_active = 1;
        }
        else{
            $layout->logo_active = 0;
        }

        if ($request->input('title_active') == 'checked')
        {
            $layout->title_active = 1;
        }
        else{
            $layout->title_active = 0;
        }
        if ($request->input('bottom_section_active') == 'checked')
        {
            $layout->bottom_section_active = 1;
        }
        else{
            $layout->bottom_section_active = 0;
        }

        $layout->site_name=$request->site_name;
        $layout->tagline=$request->tagline;
        $layout->sidebar_content=$request->sidebar_content;
        $layout->contact_text=$request->contact_text;
        $layout->contact_btn_label=$request->contact_btn_label;
        $layout->contact_btn_link=$request->contact_btn_link;
        $layout->footer=$request->footer;
        $layout->header_pdf=$request->header_pdf;
        $layout->footer_pdf=$request->footer_pdf;
        $layout->footer_csv=$request->footer_csv;
        $layout->primary_color=$request->primary_color;
        $layout->secondary_color=$request->secondary_color;
        $layout->button_color=$request->button_color;
        $layout->button_hover_color=$request->button_hover_color;
        $layout->save();

        Session::flash('message', 'Appearance updated!');
        Session::flash('status', 'success');

        return redirect('layout_edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        $page->delete();

        Session::flash('message', 'Page deleted!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    public function datasync()
    {
        $airtables = Airtables::all();

        return view('backEnd.datasync', compact('airtables'));
    }

}
