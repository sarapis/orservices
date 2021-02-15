<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardEditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layout = Layout::first();
        $page = Page::find(6);
        return view('backEnd.dashboard_setting', compact('layout', 'page'));
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
        try {
            $page = Page::whereId(6)->first();
            if ($page) {
                $page->name = 'Dashboard';
                $page->title = $request->title;
                $page->body = $request->body;
                $page->save();
            } else {
                Page::whereId(6)->create([
                    'title' => $request->title,
                    'body' => $request->body,
                    'name' => 'Dashboard'
                ]);
            }
            Session::flash('message', 'Dashboard data saved successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
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
        //
    }
}
