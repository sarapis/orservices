<?php

namespace App\Http\Controllers\frontEnd;

use App\Exports\TaxonomyExport;
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
use App\Model\TaxonomyEmail;
use App\Model\TaxonomyTerm;
use App\Model\TaxonomyType;
use App\Services\Stringtoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Image;
use Yajra\DataTables\Facades\DataTables;

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

            $request = $airtable->getContent('taxonomy_terms');

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
                        $taxonomyIds = isset($record['fields']['taxonomy']) ? $record['fields']['taxonomy'] : [];

                        $taxonomy_ids = null;
                        foreach ($taxonomyIds as $key => $taxonomyid) {
                            if ($key == 0) {
                                $taxonomy_ids = $strtointclass->string_to_int($taxonomyid);
                            } else {

                                $taxonomy_ids = $taxonomy_ids . ',' . $strtointclass->string_to_int($taxonomyid);
                            }
                        }

                        if (isset($record['fields']['x-icon_dark']) && is_array($record['fields']['x-icon_dark'])) {
                            $icon_dark = $record['fields']['x-icon_dark'];
                            foreach ($icon_dark as $key => $value) {
                                $path = $value['url'];
                                $filename = $value['filename'];
                                Image::make($path)->save(public_path('/uploads/images/' . $filename));
                                $taxonomy->category_logo = '/uploads/images/' . $filename;
                            }
                        }
                        if (isset($record['fields']['x-icon_light']) && is_array($record['fields']['x-icon_light'])) {
                            $icon_light = $record['fields']['x-icon_light'];
                            foreach ($icon_light as $key => $value) {
                                $path = $value['url'];
                                $filename = $value['filename'];
                                Image::make($path)->save(public_path('/uploads/images/' . $filename));
                                $taxonomy->category_logo_white = '/uploads/images/' . $filename;
                            }
                        }

                        $taxonomy->taxonomy = $taxonomy_ids;
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
        $taxonomies = Taxonomy::with('parent')->get();
        $taxonomieTypes = TaxonomyType::orderBy('order')->where('type', 'internal')->pluck('name', 'taxonomy_type_recordid');
        $taxonomieTypesExternal = TaxonomyType::orderBy('order')->where('type', 'external')->pluck('name', 'taxonomy_type_recordid');

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
        $languages = Language::orderBy('order')->pluck('language', 'language');
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
        $taxonomieTypes = TaxonomyType::orderBy('order')->where('type', 'internal')->pluck('name', 'taxonomy_type_recordid');
        $taxonomieTypesExternal = TaxonomyType::orderBy('order')->where('type', 'external')->pluck('name', 'taxonomy_type_recordid');
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
        $languages = Language::orderBy('order')->pluck('language', 'language');
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
            $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
            $taxonomy = new Taxonomy();
            $taxonomy->taxonomy_name = $request->taxonomy_name;
            $taxonomy->taxonomy_recordid = $taxonomy_recordid;
            $taxonomy->x_taxonomies = $request->x_taxonomies;
            $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
            $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
            $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
            $taxonomy->taxonomy_parent_name = $request->taxonomy_parent_name;
            $taxonomy->taxonomy = $request->taxonomy;
            $taxonomy->language = $request->language;
            if (str_contains($request->badge_color, '#')) {
                $badge_color = str_replace('#', '', $request->badge_color);
            } else {
                $badge_color = $request->badge_color;
            }
            $taxonomy->badge_color = $badge_color;
            $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
            $taxonomy->status = 'Unpublished';
            $taxonomy->created_by = Auth::id();
            $taxonomy->additional_taxonomy_type()->sync($request->x_taxonomies);
            $taxonomy->taxonomy_type()->sync($request->taxonomy);

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
        $taxonomy = Taxonomy::with('user')->find($id);
        $taxonomyTypeId = $taxonomy->taxonomy;
        // $row->taxonomy_type && count($row->taxonomy_type) > 0 ? $row->taxonomy_type[0]->name : ''

        if ($taxonomy->taxonomy_type && count($taxonomy->taxonomy_type) > 0) {
            $taxonomyTypeId = $taxonomy->taxonomy_type[0]->taxonomy_type_recordid;
        }

        $taxonomy_info_list = Taxonomy::orderBy('taxonomy_name')->where('taxonomy', $taxonomyTypeId)->whereNull('taxonomy_parent_name')->get();
        $taxonomy_array = [];
        foreach ($taxonomy_info_list as $value) {
            // if ($value->taxonomy_parent_name) {
            $taxonomy_array[$value->taxonomy_recordid] = $value->taxonomy_name;
            $taxonomy_child_list = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value->taxonomy_recordid . '%')->where('taxonomy', $taxonomyTypeId)->get();
            if ($taxonomy_child_list) {
                foreach ($taxonomy_child_list as $value1) {
                    if (!array_key_exists($value1->taxonomy_recordid, $taxonomy_array))
                        $taxonomy_array[$value1->taxonomy_recordid] = '- '  . $value1->taxonomy_name;
                    $taxonomy_child_list1 = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->where('taxonomy', $taxonomyTypeId)->get();
                    if ($taxonomy_child_list1) {
                        foreach ($taxonomy_child_list1 as $value2) {
                            if (!array_key_exists($value2->taxonomy_recordid, $taxonomy_array))
                                $taxonomy_array[$value2->taxonomy_recordid] = '-- '  . $value2->taxonomy_name;
                            $taxonomy_child_list2 = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value2->taxonomy_recordid . '%')->where('taxonomy', $taxonomyTypeId)->get();
                            if ($taxonomy_child_list2) {
                                // if ($value2->taxonomy_name == 'Childcare') {
                                //     dd($taxonomy_child_list2);
                                // }
                                foreach ($taxonomy_child_list2 as $value3) {
                                    if (!array_key_exists($value3->taxonomy_recordid, $taxonomy_array))
                                        $taxonomy_array[$value3->taxonomy_recordid] = '--- '  . $value3->taxonomy_name;
                                }
                            }
                        }
                    }
                }
            }
            // } else {
            //     $taxonomy_array[$value->taxonomy_recordid] = $value->taxonomy_name;
            // }
        }

        if (array_key_exists($taxonomy->taxonomy_recordid, $taxonomy_array)) {
            if (isset($taxonomy_array[$taxonomy->taxonomy_recordid])) {
                unset($taxonomy_array[$taxonomy->taxonomy_recordid]);
            }
        }
        collect($taxonomy_array);
        $parentData = $taxonomy_array;
        // if ($taxonomyTypeId) {
        //     $parentData = Taxonomy::where('taxonomy', $taxonomyTypeId)->whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');
        // } else {
        //     $parentData = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');
        // }
        if ($taxonomy->parent) {
            $parent = $taxonomy->parent;
            if ($parent) {
                // $current_taxonomy_parent = Taxonomy::where('taxonomy_recordid', $parent->taxonomy_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
                // $parentData =$parentData->toArray();
                // $parentData = $parentData->union($current_taxonomy_parent)->unique();
                $taxonomy->taxonomy_parent_name = $parent->taxonomy_recordid;
            }
        }

        if ($taxonomy->badge_color) {
            $taxonomy->badge_color = '#' . $taxonomy->badge_color;
        }
        $taxonomy->parentData = $parentData;
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
        $taxonomy->status = $request->status;
        $taxonomy->order = $request->order;
        if (str_contains($request->badge_color, '#')) {
            $badge_color = str_replace('#', '', $request->badge_color);
        } else {
            $badge_color = $request->badge_color;
        }
        $taxonomy->badge_color = $badge_color;
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

            if ($request->taxonomy) {
                $taxonomy->taxonomy = $request->taxonomy;
            }
            $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
            $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
            $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
            $taxonomy->taxonomy_parent_name = $request->taxonomy_parent_name;
            $taxonomy->language = $request->language;
            $taxonomy->status = $request->status;
            $taxonomy->order = $request->order;
            if (str_contains($request->badge_color, '#')) {
                $badge_color = str_replace('#', '', $request->badge_color);
            } else {
                $badge_color = $request->badge_color;
            }
            $taxonomy->badge_color = $badge_color;
            $taxonomy->exclude_vocabulary = $request->exclude_vocabulary;
            $taxonomy->flag = 'modified';
            $taxonomy->additional_taxonomy_type()->sync($request->x_taxonomies);
            if ($request->taxonomy) {
                $taxonomy->taxonomy_type()->sync($request->taxonomy);
            }

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
            return redirect('tb_taxonomy');
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
            Log::error('Error in saveLanguage : ' . $th);
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
    public function show_added_taxonomy()
    {
        $add_taxonomies = Taxonomy::orderBy('created_at', 'desc')->where('added_term', '1')->cursor();
        $emails = TaxonomyEmail::get();
        return view('backEnd.added_taxonomy_term.added_taxonomy', compact('add_taxonomies', 'emails'));
    }
    public function add_taxonomy_email(Request $request)
    {
        try {
            TaxonomyEmail::create([
                'email_recordid' => TaxonomyEmail::max('email_recordid') + 1,
                'email' => $request->get('email'),
                'created_by' => Auth::id()
            ]);
            Session::flash('message', 'Email added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function delete_taxonomy_email(Request $request)
    {
        try {
            if ($request->has('email_recordid')) {
                TaxonomyEmail::where('email_recordid', $request->get('email_recordid'))->delete();
                Session::flash('message', 'Email deleted successfully!');
                Session::flash('status', 'success');
            } else {
                Session::flash('message', 'Something went wrong');
                Session::flash('status', 'error');
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function edit_taxonomy_added($id)
    {
        // $taxonomy = Taxonomy::whereId($id)->first();

        // return view('backEnd.added_taxonomy_term.edit', compact('taxonomy'));
        $taxonomies = Taxonomy::whereId($id)->with('parent')->get();
        $taxonomieTypes = TaxonomyType::orderBy('order')->where('type', 'internal')->pluck('name', 'taxonomy_type_recordid');
        $taxonomieTypesExternal = TaxonomyType::orderBy('order')->where('type', 'external')->pluck('name', 'taxonomy_type_recordid');

        $taxonomieParents = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_name');
        $taxonomyAllParents = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name')->toArray();
        $taxonomyAllParents = json_encode($taxonomyAllParents);
        // $taxonomies = $taxonomies->filter(function ($value) {

        //     $taxonomy_parent_data = $value->taxonomy_parent_name ? explode(',', $value->taxonomy_parent_name) : [];
        //     $parent_name = '';
        //     foreach ($taxonomy_parent_data as $key => $recordid) {
        //         $taxonomy = Taxonomy::where('taxonomy_recordid', $recordid)->first();
        //         if ($taxonomy) {
        //             if ($key == 0) {
        //                 $parent_name = $taxonomy->taxonomy_name;
        //             } else {
        //                 $parent_name = $parent_name . ', ' . $taxonomy->taxonomy_name;
        //             }
        //         }
        //     }
        //     $value->taxonomy_parent_name = $parent_name;
        //     return true;
        // });
        $languages = Language::orderBy('order')->pluck('language', 'language');
        $source_data = Source_data::find(1);
        $layout = Layout::find(1);
        $alt_taxonomies = Alt_taxonomy::all();
        $taxonomy_parent_name = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');

        return view('backEnd.tables.tb_taxonomy', compact('taxonomies', 'source_data', 'alt_taxonomies', 'taxonomy_parent_name', 'layout', 'languages', 'taxonomieTypes', 'taxonomieParents', 'taxonomyAllParents', 'taxonomieTypesExternal', 'id'));
    }
    public function updateStatus(Request $request, $id)
    {
        try {
            $id = $request->id;
            $taxonomy = Taxonomy::whereId($id)->first();
            $taxonomy->status = $request->status;
            $taxonomy->save();

            Session::flash('message', 'Taxonomy updated successfully!');
            Session::flash('status', 'success');
            return redirect('show_added_taxonomy');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function taxonomy_export_csv(Request $request)
    {
        try {
            Excel::store(new TaxonomyExport($request), 'taxonomy_term.csv', 'csv');
            return response()->json([
                'path' => url('/csv/taxonomy_term.csv'),
                'success' => true
            ], 200);
            // return response()->download(public_path('/csv/taxonomy_term.csv'))->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            Log::error('Error in taxonomy_export_csv : ' . $th);
            return response()->json([
                'path' => '',
                'success' => false
            ], 500);
        }
    }
    public function getAllTaxonomy(Request $request, $id = null)
    {
        try {
            $taxonomies = Taxonomy::select('*');
            return DataTables::of($taxonomies)
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->created_at));
                })
                ->editColumn('taxonomy_parent_name', function ($row) {
                    return $row->parent ? $row->parent->taxonomy_name : '';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user ? $row->user->first_name : '';
                })
                ->editColumn('taxonomy', function ($row) {
                    return $row->taxonomy_type && count($row->taxonomy_type) > 0 ? $row->taxonomy_type[0]->name : '';
                })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<button class="btn btn-block btn-primary btn-sm open_modal"  value="' . $row->taxonomy_recordid . '" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>';
                        $id = $row->id;
                        $route = 'tb_taxonomy';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');

                    if ($extraData) {
                        if (isset($extraData['id']) && $extraData['id']) {
                            $query->where('id', $extraData['id']);
                        }
                        if (isset($extraData['taxonomy_select']) && $extraData['taxonomy_select']) {
                            $taxonomyType = TaxonomyType::orderBy('order')->where('name', $extraData['taxonomy_select'])->first();
                            if ($taxonomyType) {
                                $query->where('taxonomy', $taxonomyType->taxonomy_type_recordid);
                            }
                        }
                        if (isset($extraData['parent_filter']) && $extraData['parent_filter']) {
                            $parentData = Taxonomy::whereIn('taxonomy_name', $extraData['parent_filter'])->pluck('taxonomy_recordid')->toArray();
                            if (in_array('all', $extraData['parent_filter']) && in_array('none', $extraData['parent_filter'])) {
                                $query = $query;
                            } else {
                                if (in_array('all', $extraData['parent_filter'])) {
                                    $query->whereNotNull('taxonomy_parent_name');
                                }
                                if (in_array('none', $extraData['parent_filter'])) {
                                    $query->whereNull('taxonomy_parent_name');
                                }
                            }
                            if (!in_array('all', $extraData['parent_filter']) && !in_array('none', $extraData['parent_filter'])) {
                                $query->whereIn('taxonomy_parent_name', $parentData);
                            }
                        }
                    }
                    return $query;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in getAllTaxonomy : ' . $th);
        }
    }
    public function getParentTerm(Request $request)
    {
        try {
            $taxonomyTypeId = $request->get('taxonomyTypeId');
            $taxonomy_info_list = Taxonomy::orderBy('taxonomy_name')->whereNull('taxonomy_parent_name')->where('taxonomy', $taxonomyTypeId)->get();
            $taxonomy_array = [];
            foreach ($taxonomy_info_list as $value) {
                // if ($value->taxonomy_parent_name) {
                $taxonomy_array[$value->taxonomy_recordid] = $value->taxonomy_name;
                $taxonomy_child_list = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value->taxonomy_recordid . '%')->where('taxonomy', $taxonomyTypeId)->get();
                if ($taxonomy_child_list) {
                    foreach ($taxonomy_child_list as $value1) {
                        if (!array_key_exists($value1->taxonomy_recordid, $taxonomy_array))
                            $taxonomy_array[$value1->taxonomy_recordid] = '- '  . $value1->taxonomy_name;
                        $taxonomy_child_list1 = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->where('taxonomy', $taxonomyTypeId)->get();
                        if ($taxonomy_child_list1) {
                            foreach ($taxonomy_child_list1 as $value2) {
                                if (!array_key_exists($value2->taxonomy_recordid, $taxonomy_array))
                                    $taxonomy_array[$value2->taxonomy_recordid] = '-- '  . $value2->taxonomy_name;
                                $taxonomy_child_list2 = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value2->taxonomy_recordid . '%')->where('taxonomy', $taxonomyTypeId)->get();
                                if ($taxonomy_child_list2) {
                                    foreach ($taxonomy_child_list2 as $value3) {
                                        if (!array_key_exists($value3->taxonomy_recordid, $taxonomy_array))
                                            $taxonomy_array[$value3->taxonomy_recordid] = '--- '  . $value3->taxonomy_name;
                                    }
                                }
                            }
                        }
                    }
                }
                // } else {
                //     $taxonomy_array[$value->taxonomy_recordid] = $value->taxonomy_name;
                // }
            }

            collect($taxonomy_array);
            $parentData = $taxonomy_array;
            // if ($taxonomyTypeId) {
            //     $parentData = Taxonomy::where('taxonomy', $taxonomyTypeId)->whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');
            // } else {
            //     $parentData = Taxonomy::whereNull('taxonomy_parent_name')->pluck('taxonomy_name', 'taxonomy_recordid');
            // }
            return response()->json([
                'data' => $parentData,
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
