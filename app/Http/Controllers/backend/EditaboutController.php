<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EditaboutController extends Controller
{
    protected function validator(Request $request, $id = '')
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'body' => 'required',
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

        $page = Page::findOrFail(2);

        return view('backEnd.pages.edit_about', compact('page', 'layout'));
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

        Page::create($request->all());

        $layout = Layout::find(1);
        if ($layout) {
            if ($request->activate_about_home) {
                $layout->activate_about_home = 1;
            } else {
                $layout->activate_about_home = 0;
            }
            $layout->save();
        }

        Session::flash('message', 'Page added!');
        Session::flash('status', 'success');

        return redirect('pages');
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
            // if ($this->validator($request, Auth::id())->fails()) {

            //     return redirect()->back()
            //     ->withErrors($this->validator($request))
            //     ->withInput();
            // }
            $page = Page::findOrFail($id);
            $page->update($request->all());
            $layout = Layout::find(1);
            if ($layout) {
                if ($request->activate_about_home) {
                    $layout->activate_about_home = 1;
                } else {
                    $layout->activate_about_home = 0;
                }
                $layout->save();
            }

            Session::flash('message', 'Page updated!');
            Session::flash('status', 'success');

            return redirect('about_edit');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');

            return redirect('about_edit');
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
}
