<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\CodeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = CodeCategory::cursor();
        return view('backEnd.sdoh_domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.sdoh_domains.create');
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
            'name' => 'required'
        ]);
        try {
            CodeCategory::create([
                'name' => $request->name
            ]);
            DB::commit();
            return redirect()->to('code_categories')->with('success', 'Domain created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('code_categories')->with('error', $th->getMessage());
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
        $domain = CodeCategory::whereId($id)->first();
        return view('backEnd.sdoh_domains.edit', compact('domain'));
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
            'name' => 'required'
        ]);
        try {
            CodeCategory::whereId($id)->update([
                'name' => $request->name
            ]);
            DB::commit();
            return redirect()->to('code_categories')->with('success', 'Domain updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('code_categories')->with('error', $th->getMessage());
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
            CodeCategory::whereId($id)->delete();

            return redirect()->to('code_categories')->with('success', 'Domain Deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('code_categories')->with('error', $th->getMessage());
        }
    }
}
