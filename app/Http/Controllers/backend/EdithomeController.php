<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Image;

class EdithomeController extends Controller
{
    protected function validator(Request $request, $id = '')
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
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
        try {
            $page = Page::findOrFail(1);
            $layout = Layout::find(1);
            return view('backEnd.pages.edit_home', compact('page', 'layout'));
        } catch (\Throwable $th) {
            dd($th);
        }
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
            $layout = Layout::find(1);
            $layout->sidebar_content = $request->sidebar_content;
            // $layout->sidebar_content_part_1 = $request->sidebar_content_part_1;
            // $layout->sidebar_content_part_2 = $request->sidebar_content_part_2;
            // $layout->sidebar_content_part_3 = $request->sidebar_content_part_3;
            $layout->banner_text1 = $request->banner_text1;
            $layout->banner_text2 = $request->banner_text2;

            if ($request->hasFile('home_bk_img_file')) {
                $homepage_background = $request->file('home_bk_img_file');
                $filename = time() . '_part1' . '.' . $homepage_background->getClientOriginalExtension();
                Image::make($homepage_background)->resize(1800, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('/uploads/images/' . $filename));
                $layout->homepage_background =  $filename;
            }
            // part 1
            if ($request->hasFile('part_1_image')) {
                $part_1_image = $request->file('part_1_image');
                $filename = time() . '_part2' . '.' . $part_1_image->getClientOriginalExtension();
                Image::make($part_1_image)->save(public_path('/uploads/images/' . $filename));
                $layout->part_1_image = '/uploads/images/' . $filename;
            }
            // part 2
            // if ($request->hasFile('part_2_image')) {
            //     $part_2_image = $request->file('part_2_image');
            //     $filename = time() . '_part3' . '.' . $part_2_image->getClientOriginalExtension();
            //     Image::make($part_2_image)->save(public_path('/uploads/images/' . $filename));
            //     $layout->part_2_image = '/uploads/images/' . $filename;
            // }
            // part 3
            // if ($request->hasFile('part_3_image')) {
            //     $part_3_image = $request->file('part_3_image');
            //     $filename = time() . '.' . $part_3_image->getClientOriginalExtension();
            //     Image::make($part_3_image)->save(public_path('/uploads/images/' . $filename));
            //     $layout->part_3_image = '/uploads/images/' . $filename;
            // }
            $layout->save();

            if ($this->validator($request, Auth::id())->fails()) {

                return redirect()->back()
                    ->withErrors($this->validator($request))
                    ->withInput();
            }

            $page = Page::findOrFail($id);
            $page->update($request->all());

            Session::flash('message', 'Page updated!');
            Session::flash('status', 'success');

            return redirect('home_edit');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('home_edit');
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
