<?php

namespace App\Http\Controllers\frontEnd;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Imports\ServiceTaxonomyImport;
use App\Imports\TaxonomyImport;
use App\Model\AdditionalTaxonomy;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\Alt_taxonomy;
use App\Model\CSV_Source;
use App\Model\Language;
use App\Model\Layout;
use App\Model\ServiceTaxonomy;
use App\Model\Source_data;
use App\Model\Taxonomy;
use App\Model\TaxonomyTerm;
use App\Model\TaxonomyType;
use App\Services\Stringtoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class TaxonomyController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info) {
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->api_key = $api_key;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Taxonomy::truncate();
        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key' => $api_key,
            'base' => $base_url,
        ));

        $request = $airtable->getContent('taxonomy');

        do {

            $response = $request->getResponse();

            $airtable_response = json_decode($response, true);

            foreach ($airtable_response['records'] as $record) {

                $taxonomy = new Taxonomy();
                $strtointclass = new Stringtoint();

                $taxonomy->taxonomy_recordid = $strtointclass->string_to_int($record['id']);
                $taxonomy->taxonomy_id = $record['id'];
                // $taxonomy->taxonomy_recordid = $record[ 'id' ];
                $taxonomy->taxonomy_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                $taxonomy->taxonomy_parent_name = isset($record['fields']['parent_name']) ? implode(",", $record['fields']['parent_name']) : null;
                if ($taxonomy->taxonomy_parent_name != null) {
                    $taxonomy->taxonomy_parent_name = $strtointclass->string_to_int($taxonomy->taxonomy_parent_name);
                }
                $taxonomy->taxonomy_vocabulary = isset($record['fields']['vocabulary']) ? $record['fields']['vocabulary'] : null;
                $taxonomy->taxonomy_x_description = isset($record['fields']['description-x']) ? $record['fields']['description-x'] : null;
                $taxonomy->taxonomy_x_notes = isset($record['fields']['notes-x']) ? $record['fields']['notes-x'] : null;

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services'] as $value) {

                        $taxonomyservice = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $taxonomy->taxonomy_services = $taxonomy->taxonomy_services . ',' . $taxonomyservice;
                        } else {
                            $taxonomy->taxonomy_services = $taxonomyservice;
                        }

                        $i++;
                    }
                }

                $taxonomy->save();
            }
        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Taxonomy')->first();
        $airtable->records = Taxonomy::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }
    public function airtable_v2($api_key, $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->api_key = $api_key;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            // Taxonomy::truncate();
            // $airtable = new Airtable(array(
            //     'api_key'   => env('AIRTABLE_API_KEY'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'api_key' => $api_key,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('taxonomy_term');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);
                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_taxonomy = Taxonomy::where('taxonomy_recordid', $recordId)->where('taxonomy_name', isset($record['fields']['term']) ? $record['fields']['term'] : null)->first();

                    if ($old_taxonomy == null) {
                        $taxonomy = new Taxonomy();
                        $strtointclass = new Stringtoint();

                        $taxonomy->taxonomy_recordid = $strtointclass->string_to_int($record['id']);
                        $taxonomy->taxonomy_id = $record['id'];
                        // $taxonomy->taxonomy_recordid = $record[ 'id' ];
                        $taxonomy->taxonomy_name = isset($record['fields']['term']) ? $record['fields']['term'] : null;
                        // $taxonomy->taxonomy_parent_name = isset($record['fields']['parent_name']) ? implode(",", $record['fields']['parent_name']) : null;
                        $parent_names = isset($record['fields']['parent_name']) ? $record['fields']['parent_name'] : [];
                        $parent_name_ids = null;
                        foreach ($parent_names as $key => $parent_name) {
                            if ($key == 0) {
                                $parent_name_ids = $strtointclass->string_to_int($parent_name);
                            } else {

                                $parent_name_ids = $parent_name_ids . ',' . $strtointclass->string_to_int($parent_name);
                            }
                        }
                        $taxonomy->taxonomy_parent_name = $parent_name_ids;
                        // if ($taxonomy->taxonomy_parent_name != null) {
                        //     $taxonomy->taxonomy_parent_name = $strtointclass->string_to_int($taxonomy->taxonomy_parent_name);
                        // }
                        $taxonomy->taxonomy_vocabulary = isset($record['fields']['vocabulary']) ? $record['fields']['vocabulary'] : null;
                        $taxonomyIds = isset($record['fields']['taxonomy']) ? $record['fields']['taxonomy'] : '';
                        // $taxonomyType = TaxonomyType::where('name', $taxonomyIds)->first();
                        // $taxonomy_ids = null;
                        // foreach ($taxonomyIds as $key => $taxonomyid) {
                        //     if ($key == 0) {
                        //         $taxonomy_ids = $strtointclass->string_to_int($taxonomyid);
                        //     } else {

                        //         $taxonomy_ids = $taxonomy_ids . ',' . $strtointclass->string_to_int($taxonomyid);
                        //     }
                        // }
                        $taxonomy->taxonomy = $taxonomyIds;
                        $xtaxonomies = isset($record['fields']['x-taxonomies']) ? $record['fields']['x-taxonomies'] : [];
                        $xtaxonomies_ids = null;
                        foreach ($xtaxonomies as $key => $xtaxonomy) {
                            if ($key == 0) {
                                $xtaxonomies_ids = $strtointclass->string_to_int($xtaxonomy);
                            } else {

                                $xtaxonomies_ids = $xtaxonomies_ids . ',' . $strtointclass->string_to_int($xtaxonomy);
                            }
                        }
                        $taxonomy->x_taxonomies = $xtaxonomies_ids;
                        $taxonomy->taxonomy_x_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;
                        $taxonomy->taxonomy_x_notes = isset($record['fields']['x-notes']) ? $record['fields']['x-notes'] : null;

                        $color = substr(str_shuffle('AABBCCDDEEFF00112233445566778899AABBCCDDEEFF00112233445566778899AABBCCDDEEFF00112233445566778899'), 0, 6);
                        $taxonomy->badge_color = $color;
                        if (isset($record['fields']['services'])) {
                            $i = 0;
                            foreach ($record['fields']['services'] as $value) {

                                $taxonomyservice = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $taxonomy->taxonomy_services = $taxonomy->taxonomy_services . ',' . $taxonomyservice;
                                } else {
                                    $taxonomy->taxonomy_services = $taxonomyservice;
                                }

                                $i++;
                            }
                        }

                        $taxonomy->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Taxonomy_Term')->first();
            $airtable->records = Taxonomy::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Taxonomy: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function csv(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename = $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'taxonomy.csv') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This CSV is not correct.',
            //     );
            //     return $response;
            // }

            // if (count($data) > 0) {
            //     $csv_header_fields = [];
            //     foreach ($data[0] as $key => $value) {
            //         $csv_header_fields[] = $key;
            //     }
            //     $csv_data = $data;
            // }

            Excel::import(new TaxonomyImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Taxonomy')->first();
            $csv_source->records = Taxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Taxonomy imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'false',
                'result' => $th->getMessage(),
            );
            return $response;
        }
    }

    public function csv_services_taxonomy(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename = $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'services_taxonomy.csv') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This CSV is not correct.',
            //     );
            //     return $response;
            // }

            // if (count($data) > 0) {
            //     $csv_header_fields = [];
            //     foreach ($data[0] as $key => $value) {
            //         $csv_header_fields[] = $key;
            //     }
            //     $csv_data = $data;
            // }

            ServiceTaxonomy::truncate();

            Excel::import(new ServiceTaxonomyImport, $request->file('csv_file'));

            $date = date("Y/m/d H:i:s");
            $csv_source = CSV_Source::where('name', '=', 'Services_taxonomy')->first();
            $csv_source->records = ServiceTaxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Service txonomy imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'false',
                'result' => $th->getMessage(),
            );
            return $response;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxonomies = Taxonomy::get();
        $taxonomieTypes = TaxonomyType::where('type', 'internal')->pluck('name', 'taxonomy_type_recordid');
        $taxonomieTypesExternal = TaxonomyType::where('type', 'external')->pluck('name', 'taxonomy_type_recordid');

        $taxonomieParents = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_name');
        $taxonomyAllParents = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name')->toArray();
        $taxonomyAllParents = json_encode($taxonomyAllParents);
        $taxonomies = $taxonomies->filter(function ($value) {

            $taxonomy_parent_data = $value->taxonomy_parent_name ? explode(',', $value->taxonomy_parent_name) : [];
            $parent_name = '';
            foreach ($taxonomy_parent_data as $key => $recordid) {
                $taxonomy = Taxonomy::where('taxonomy_recordid', $recordid)->first();
                if ($taxonomy) {
                    if ($key == 0) {
                        $parent_name = $taxonomy->taxonomy_name;
                    } else {
                        $parent_name = $parent_name . ', ' . $taxonomy->taxonomy_name;
                    }
                }
            }
            $value->taxonomy_parent_name = $parent_name;
            return true;
        });
        $languages = Language::pluck('language', 'language');
        $source_data = Source_data::find(1);
        $layout = Layout::find(1);
        $alt_taxonomies = Alt_taxonomy::all();
        $taxonomy_parent_name = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');

        return view('backEnd.tables.tb_taxonomy', compact('taxonomies', 'source_data', 'alt_taxonomies', 'taxonomy_parent_name', 'layout', 'languages', 'taxonomieTypes', 'taxonomieParents', 'taxonomyAllParents', 'taxonomieTypesExternal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taxonomies = Taxonomy::get();
        $taxonomieTypes = TaxonomyType::where('type', 'internal')->pluck('name', 'taxonomy_type_recordid');
        $taxonomieTypesExternal = TaxonomyType::where('type', 'external')->pluck('name', 'taxonomy_type_recordid');
        $taxonomies = $taxonomies->filter(function ($value) {

            $taxonomy_parent_data = $value->taxonomy_parent_name ? explode(',', $value->taxonomy_parent_name) : [];
            $parent_name = '';
            foreach ($taxonomy_parent_data as $key => $recordid) {
                $taxonomy = Taxonomy::where('taxonomy_recordid', $recordid)->first();
                if ($taxonomy) {
                    if ($key == 0) {
                        $parent_name = $taxonomy->taxonomy_name;
                    } else {
                        $parent_name = $parent_name . ', ' . $taxonomy->taxonomy_name;
                    }
                }
            }
            $value->taxonomy_parent_name = $parent_name;
            return true;
        });
        $languages = Language::pluck('language', 'language');
        $source_data = Source_data::find(1);
        $layout = Layout::find(1);
        $alt_taxonomies = Alt_taxonomy::all();
        $taxonomy_parent_name = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');
        return view('backEnd.service_term.create', compact('taxonomies', 'source_data', 'alt_taxonomies', 'taxonomy_parent_name', 'layout', 'languages', 'taxonomieTypes', 'taxonomieTypesExternal'));
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
            'taxonomy_name' => 'required'
        ]);
        try {
            $taxonomy = new Taxonomy();
            $taxonomy->taxonomy_name = $request->taxonomy_name;
            $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
            $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
            $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
            $taxonomy->taxonomy_parent_name = $request->taxonomy_parent_name;
            $taxonomy->taxonomy = $request->taxonomy;
            $taxonomy->language = $request->language;
            $taxonomy->badge_color = $request->badge_color;
            $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;

            if ($request->hasFile('category_logo')) {
                $category_logo = $request->file('category_logo');
                $name = time() . 'category_logo_1' . $category_logo->getClientOriginalExtension();
                $path = public_path('uploads/images');
                $category_logo->move($path, $name);
                $taxonomy->category_logo = '/uploads/images' . $name;
            }
            if ($request->hasFile('category_logo_white')) {
                $category_logo_white = $request->file('category_logo_white');
                $name = time() . 'category_logo_white_2' . $category_logo_white->getClientOriginalExtension();
                $path = public_path('uploads/images');
                $category_logo_white->move($path, $name);
                $taxonomy->category_logo_white = '/uploads/images' . $name;
            }
            $taxonomy->save();
            Session::flash('message', 'Taxonomy Created successfully!');
            Session::flash('status', 'success');
            return redirect('/tb_taxonomy');
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
        $taxonomy = Taxonomy::find($id);
        return response()->json($taxonomy);
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
        $taxonomy = Taxonomy::find($id);
        $taxonomy->taxonomy_name = $request->taxonomy_name;
        $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
        $taxonomy->x_taxonomies = $request->x_taxonomies;
        $taxonomy->taxonomy = $request->taxonomy;
        $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
        $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
        $taxonomy->taxonomy_parent_name = $request->taxonomy_parent_name;
        $taxonomy->taxonomy = $request->taxonomy;
        $taxonomy->language = $request->language;
        $taxonomy->badge_color = $request->badge_color;
        $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
        $taxonomy->flag = 'modified';

        if ($request->hasFile('category_logo')) {
            $category_logo = $request->file('category_logo');
            $name = time() . 'category_logo_' . $id . $category_logo->getClientOriginalExtension();
            $path = public_path('uploads/images');
            $category_logo->move($path, $name);
            $taxonomy->category_logo = '/uploads/images' . $name;
        }
        if ($request->hasFile('category_logo_white')) {
            $category_logo_white = $request->file('category_logo_white');
            $name = time() . 'category_logo_white_' . $id . $category_logo_white->getClientOriginalExtension();
            $path = public_path('uploads/images');
            $category_logo_white->move($path, $name);
            $taxonomy->category_logo_white = '/uploads/images' . $name;
        }



        $taxonomy->save();

        return response()->json($taxonomy);
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
            $taxonomy = Taxonomy::whereId($id)->first();
            ServiceTaxonomy::where('taxonomy_recordid', $taxonomy->taxonomy_recordid)->delete();
            TaxonomyTerm::where('taxonomy_recordid', $taxonomy->taxonomy_recordid)->delete();
            AdditionalTaxonomy::where('taxonomy_recordid', $taxonomy->taxonomy_recordid)->delete();
            Taxonomy::whereId($id)->delete();
            Session::flash('message', 'Taxonomy deleted successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function taxonommyUpdate(Request $request)
    {
        try {
            $id = $request->id;
            $taxonomy = Taxonomy::find($id);
            $taxonomy->taxonomy_name = $request->taxonomy_name;
            $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
            $taxonomy->x_taxonomies = $request->x_taxonomies;
            $taxonomy->taxonomy = $request->taxonomy;
            $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
            $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
            $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
            $taxonomy->taxonomy_parent_name = $request->taxonomy_parent_name;
            $taxonomy->taxonomy = $request->taxonomy;
            $taxonomy->language = $request->language;
            $taxonomy->order = $request->order;
            $taxonomy->badge_color = $request->badge_color;
            $taxonomy->exclude_vocabulary = $request->exclude_vocabulary;
            $taxonomy->flag = 'modified';
            $taxonomy->additional_taxonomy_type()->sync($request->x_taxonomies);
            $taxonomy->taxonomy_type()->sync($request->taxonomy);

            if ($request->hasFile('category_logo')) {
                $category_logo = $request->file('category_logo');
                $name = time() . 'category_logo_' . $id . '.' . $category_logo->getClientOriginalExtension();
                $path = public_path('uploads/images/');
                $category_logo->move($path, $name);
                $taxonomy->category_logo = '/uploads/images/' . $name;
            }
            if ($request->hasFile('category_logo_white')) {
                $category_logo_white = $request->file('category_logo_white');
                $name = time() . 'category_logo_white_' . $id . '.' . $category_logo_white->getClientOriginalExtension();
                $path = public_path('uploads/images/');
                $category_logo_white->move($path, $name);
                $taxonomy->category_logo_white = '/uploads/images/' . $name;
            }



            $taxonomy->save();
            Session::flash('message', 'Taxonomy update successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function saveLanguage(Request $request)
    {
        try {
            $id = $request->id;
            $taxonomy = Taxonomy::whereId($id)->first();
            $taxonomy->language = $request->value;
            $taxonomy->save();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function save_vocabulary(Request $request)
    {
        try {
            $layout = Layout::whereId(1)->first();
            if ($layout) {
                $layout->exclude_vocabulary = $request->exclude_vocabulary;
                $layout->save();
            }
            Session::flash('message', 'Vocabulary saved successfully!');
            Session::flash('status', 'success');
            return redirect('tb_taxonomy');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
}
