<?php

namespace App\Http\Controllers\backend;

use App\Exports\zip\TaxonomyTypeZipExport;
use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Model\AdditionalTaxonomy;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\TaxonomyTerm;
use App\Model\TaxonomyType;
use App\Services\Stringtoint;
use App\Services\TaxonomyTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TaxonomyTypeController extends Controller
{

    public $taxonomyTypeService;

    public function __construct(TaxonomyTypeService $taxonomyTypeService)
    {
        $this->taxonomyTypeService = $taxonomyTypeService;
    }

    public function airtable_v2($access_token, $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->access_token = $access_token;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            // TaxonomyType::truncate();
            // TaxonomyTerm::truncate();
            // AdditionalTaxonomy::truncate();
            // $airtable = new Airtable(array(
            //     'api_key'   => env('AIRTABLE_API_KEY'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));
            $request = $airtable->getContent('x-taxonomies');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);


                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_taxonomy_type = TaxonomyType::where('taxonomy_type_recordid', $recordId)->where('name', isset($record['fields']['Name']) ? $record['fields']['Name'] : null)->first();
                    if ($old_taxonomy_type == null) {
                        $taxonomy = new TaxonomyType();
                        $strtointclass = new Stringtoint();

                        $taxonomy->taxonomy_type_recordid = $strtointclass->string_to_int($record['id']);
                        $taxonomy->name = isset($record['fields']['Name']) ? $record['fields']['Name'] : null;
                        $taxonomy->type = isset($record['fields']['Type']) ? $record['fields']['Type'] : null;
                        $taxonomy->reference_url = isset($record['fields']['Reference URL']) ? $record['fields']['Reference URL'] : null;
                        $taxonomy->notes = isset($record['fields']['Notes']) ? $record['fields']['Notes'] : null;

                        $taxonomy_terms = isset($record['fields']['taxonomy_term']) ? $record['fields']['taxonomy_term'] : [];
                        $taxonomy_terms_ids = [];
                        foreach ($taxonomy_terms as $key => $taxonomy_term_id) {
                            $taxonomy_terms_ids[] = $strtointclass->string_to_int($taxonomy_term_id);
                        }
                        $taxonomy->taxonomy_term()->sync($taxonomy_terms_ids);

                        $additional_taxonomy_terms = isset($record['fields']['additional taxonomies']) ? $record['fields']['additional taxonomies'] : [];
                        $additional_taxonomy_terms_ids = [];
                        foreach ($additional_taxonomy_terms as $key => $additional_taxonomy_terms_id) {
                            $additional_taxonomy_terms_ids[] = $strtointclass->string_to_int($additional_taxonomy_terms_id);
                        }
                        $taxonomy->additional_taxonomy_term()->sync($additional_taxonomy_terms_ids);

                        $taxonomy->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'x_Taxonomy')->first();
            $airtable->records = TaxonomyType::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in x-taxonomies: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function airtable_v3($access_token, $base_url)
    {
        $this->taxonomyTypeService->import_airtable_v3($access_token, $base_url);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $taxonomy_types = TaxonomyType::get();

            if (!$request->ajax()) {
                return view('backEnd.taxonomy_type.index', compact('taxonomy_types'));
            }
            $taxonomy_types = TaxonomyType::select('*');
            return DataTables::of($taxonomy_types)
                // ->editColumn('detail_services', function ($row) {
                //     $service_name = '';
                //     if ($row->detail_services) {
                //         $serviceIds = explode(',', $row->detail_services);

                //         $services = Service::whereIn('service_recordid', $serviceIds)->pluck('service_name')->toArray();
                //         $service_name .= implode(',', $services);
                //     }
                //     return $service_name;
                // })
                // ->editColumn('detail_organizations', function ($row) {
                //     return $row->organization ? $row->organization->organization_name : '';
                // })
                // ->editColumn('detail_locations', function ($row) {
                //     return $row->location && count($row->location) > 0 && $row->location[0] ? $row->location[0]->location_name : '';
                // })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("taxonomy_types.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'taxonomy_types';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                // ->filter(function ($query) use ($request) {
                //     $extraData = $request->get('extraData');

                //     if ($extraData) {
                //         $extraData['detail_type'] = array_filter($extraData['detail_type']);
                //         if (isset($extraData['detail_type']) && $extraData['detail_type'] != null) {
                //             $query = $query->whereIn('detail_type', $extraData['detail_type']);
                //         }
                //     }
                //     return $query;
                // })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in Taxonomytype index : ' . $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.taxonomy_type.create');
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
            'name' => 'required',
            'order' => 'required|unique:taxonomy_types,order',
        ]);
        try {
            TaxonomyType::create([
                'name' => $request->name,
                'order' => $request->order,
                'type' => $request->type,
                'reference_url' => $request->reference_url,
                'version' => $request->version,
                'notes' => $request->notes,
            ]);
            Session::flash('message', 'Success! Taxonomy type is created successfully.');
            Session::flash('status', 'success');
            return redirect('/taxonomy_types');
        } catch (\Throwable $th) {
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
        $taxonomy_type = TaxonomyType::whereId($id)->first();
        return view('backEnd.taxonomy_type.edit', compact('taxonomy_type'));
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
            'name' => 'required',
            'order' => 'required',
        ]);
        try {
            TaxonomyType::whereId($id)->update([
                'name' => $request->name,
                'order' => $request->order,
                'type' => $request->type,
                'reference_url' => $request->reference_url,
                'version' => $request->version,
                'notes' => $request->notes,
            ]);
            Session::flash('message', 'Success! Taxonomy type is updated successfully.');
            Session::flash('status', 'success');
            return redirect('/taxonomy_types');
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
        try {
            TaxonomyType::whereId($id)->delete();
            Session::flash('message', 'Success! Taxonomy type is deleted successfully.');
            Session::flash('status', 'success');
            return redirect('/taxonomy_types');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
        }
    }

    public function export()
    {
        try {
            return Excel::download(new TaxonomyTypeZipExport(false), 'Taxonomies.csv');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
