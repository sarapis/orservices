<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Alt_taxonomy;
use App\Taxonomy;
use App\Servicetaxonomy;
use App\AltTaxonomiesTermRelation;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

class AltTaxonomyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alt_taxonomies = Alt_taxonomy::orderBy('id')->paginate(20);
        $counts = [];
        // $terms = $alt_taxonomies->terms();
        foreach ($alt_taxonomies as $key => $alt_taxonomy) {
            $id = $alt_taxonomy->id;
            $tmp_alt_taxonomy = AltTaxonomiesTermRelation::where('alt_taxonomy_id','=',$id)->get();
            //exit();
            // var_dump($tmp_alt_taxonomy->terms()->allRelatedIds());
            // exit;
            //$count = count($alt_taxonomy->terms()->allRelatedIds());
            // var_dump($terms);    
            $count = count($tmp_alt_taxonomy);
            array_push($counts, $count);
        }
        // var_dump($alt_taxonomies);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_alt_taxonomy', compact('alt_taxonomies', 'counts', 'source_data'));
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
        $alt_taxonomy= new Alt_taxonomy();
        $alt_taxonomy->alt_taxonomy_name = $request->alt_taxonomy_name;
        $alt_taxonomy->alt_taxonomy_vocabulary = $request->alt_taxonomy_vocabulary;
        $alt_taxonomy->save();

        return response()->json($alt_taxonomy);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alt_taxonomy= Alt_taxonomy::find($id);
        return response()->json($alt_taxonomy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open_terms($id)
    {
        $alt_taxonomy = Alt_taxonomy::find($id);
        $alt_taxonomy_name = $alt_taxonomy->alt_taxonomy_name;
        $terms = AltTaxonomiesTermRelation::where('alt_taxonomy_id','=',$id)->get();
        $all_terms = Taxonomy::all()->toArray();
        return response()->json(array('all_terms' => $all_terms, 'terms' => $terms, 'alt_taxonomy_name' => $alt_taxonomy_name));

    }

    public function operation(Request $request){
        $checked_terms_list = $request->input("checked_terms");
        $id = $request->input("alt_taxonomy_id");             
        $alt_taxonomy = Alt_taxonomy::find($id);
        $alt_taxonomy->terms()->sync($checked_terms_list);
        return redirect('tb_alt_taxonomy');
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
        $alt_taxonomy = Alt_taxonomy::find($id);
        $alt_taxonomy->alt_taxonomy_name = $request->alt_taxonomy_name;
        $alt_taxonomy->alt_taxonomy_vocabulary = $request->alt_taxonomy_vocabulary;
        $alt_taxonomy->save();

        return response()->json($alt_taxonomy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alt_taxonomy = Alt_taxonomy::destroy($id);
        return response()->json($alt_taxonomy);
    }
}
