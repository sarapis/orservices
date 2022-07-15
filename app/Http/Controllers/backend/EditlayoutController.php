<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use App\Model\Airtables;
use App\Model\Layout;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Image;

class EditlayoutController extends Controller
{
    protected function validator(Request $request, $id = '')
    {
        return Validator::make($request->all(), []);
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
        if ($this->validator($request, Auth::id())->fails()) {

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
        try {
            // if ($this->validator($request,Sentinel::getUser()->id)->fails()) {

            //     return redirect()->back()
            //             ->withErrors($this->validator($request))
            //             ->withInput();
            // }
            $layout = Layout::find($id);
            if ($request->hasFile('logo')) {
                $companylogo = $request->file('logo');
                $filename = time() . '.' . $companylogo->getClientOriginalExtension();
                Image::make($companylogo)->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('/uploads/images/' . $filename));
                $layout->logo = $filename;
            }
            if ($request->input('logo_active') == 'checked') {
                $layout->logo_active = 1;
            } else {
                $layout->logo_active = 0;
            }
            if ($request->input('site_title_active') == 'checked') {
                $layout->site_title_active = 1;
            } else {
                $layout->site_title_active = 0;
            }
            if ($request->organization_share_button) {
                $layout->organization_share_button = 1;
            } else {
                $layout->organization_share_button = 0;
            }
            if ($request->service_share_button) {
                $layout->service_share_button = 1;
            } else {
                $layout->service_share_button = 0;
            }
            if ($request->show_classification) {
                $layout->show_classification = 'yes';
            } else {
                $layout->show_classification = 'no';
            }
            $layout->site_name = $request->site_name;
            $layout->tagline = $request->tagline;
            $layout->contact_text = $request->contact_text;

            $layout->contact_btn_label = $request->contact_btn_label;
            $layout->contact_btn_link = $request->contact_btn_link;
            $layout->footer = $request->footer;
            // $layout->header_pdf = $request->header_pdf;
            // $layout->footer_pdf = $request->footer_pdf;
            // $layout->footer_csv = $request->footer_csv;
            $layout->primary_color = $request->primary_color;
            $layout->secondary_color = $request->secondary_color;
            $layout->button_color = $request->button_color;
            $layout->button_hover_color = $request->button_hover_color;
            $layout->title_link_color = $request->title_link_color;
            $layout->top_menu_color = $request->top_menu_color;
            $layout->top_menu_link_color = $request->top_menu_link_color;
            $layout->menu_title_color = $request->menu_title_color;
            $layout->top_menu_link_hover_color = $request->top_menu_link_hover_color;
            $layout->top_menu_link_hover_background_color = $request->top_menu_link_hover_background_color;
            $layout->submenu_highlight_color = $request->submenu_highlight_color;
            $layout->save();

            Session::flash('message', 'Appearance updated!');
            Session::flash('status', 'success');

            return redirect('layout_edit');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');

            return redirect('layout_edit');
        }
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
    public function dowload_settings()
    {
        $layout = Layout::find(1);

        return view('backEnd.pages.downloads_settings', compact('layout'));
    }
    public function save_dowload_settings(Request $request, $id)
    {
        try {
            Layout::whereId($id)->update([
                'header_pdf' => $request->header_pdf,
                'footer_pdf' => $request->footer_pdf,
                'footer_csv' => $request->footer_csv,
                'display_download_menu' => $request->display_download_menu,
                'display_download_pdf' => $request->display_download_pdf,
                'display_download_csv' => $request->display_download_csv,
            ]);
            Session::flash('message', 'Download Settings updated!');
            Session::flash('status', 'success');

            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
}
