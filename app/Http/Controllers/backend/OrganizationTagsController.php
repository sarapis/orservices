<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Organization;
use App\Model\OrganizationTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OrganizationTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $layout = Layout::first();
            $organization_tags = OrganizationTag::get();

            if (!$request->ajax()) {
                return view('backEnd.organization_tag.index', compact('organization_tags', 'layout'));
            }
            $organization_tags = OrganizationTag::select('*');
            return DataTables::of($organization_tags)
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("organization_tags.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'organization_tags';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.organization_tag.create');
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
            'tag' => 'required'
        ]);
        try {
            DB::beginTransaction();
            OrganizationTag::create([
                'tag' => $request->tag,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Tag added successfully!');
            Session::flash('status', 'success');
            return redirect('organization_tags');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
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
        $organization_tag = OrganizationTag::whereId($id)->first();
        return view('backEnd.organization_tag.edit', compact('organization_tag'));
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
            'tag' => 'required'
        ]);
        try {
            DB::beginTransaction();
            OrganizationTag::whereId($id)->update([
                'tag' => $request->tag,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Tag updated successfully!');
            Session::flash('status', 'success');
            return redirect('organization_tags');
        } catch (\Throwable $th) {
            DB::rollback();
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
        try {
            DB::beginTransaction();
            OrganizationTag::whereId($id)->delete();
            DB::commit();
            return redirect()->to('organization_tags')->with('success', 'Tag deleted successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->to('organization_tags')->with('error', $th->getMessage());
        }
    }
    public function changeTag()
    {
        try {
            $organizations = Organization::get();
            foreach ($organizations as $key => $value) {
                $tagData = $value->organization_tag;
                $orgTag = [];
                if ($tagData) {
                    $tags = explode(',', $tagData);
                    foreach ($tags as $key1 => $value1) {
                        $organization_tag = OrganizationTag::where('tag', 'LIKE', '%' . $value1 . '%')->first();
                        if ($organization_tag) {
                            $orgTag[] = $organization_tag->id;
                        } else {
                            $organization_tag = OrganizationTag::create([
                                'tag' => $value1
                            ]);
                            $orgTag[] = $organization_tag->id;
                        }
                    }
                }
                if (!empty($orgTag)) {
                    $organization = Organization::whereId($value->id)->first();
                    $organization->organization_tag = implode(',', $orgTag);
                    $organization->save();
                }
            }
            return 'done';
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
