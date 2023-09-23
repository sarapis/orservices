<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\CodeCategory;
use App\Model\CodeLedger;
use App\Model\Map;
use App\Model\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TerminologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $domain = [];
        $code_selected = [];
        if ($request->domain) {
            $domain[] = $request->domain;
        }
        if ($request->codes) {
            $code_selected[] = $request->codes;
        }
        // $category = Code::whereNotNull('category')->where('category', '!=', '')->pluck('category', 'category')->unique();
        $category = CodeCategory::pluck('name', 'id')->unique();
        $resource = Code::whereNotNull('resource')->where('resource', '!=', '')->pluck('resource', 'resource')->unique();
        $resource_element = Code::whereNotNull('resource_element')->where('resource_element', '!=', '')->pluck('resource_element', 'resource_element')->unique();
        $groupingids = Code::whereNotNull('grouping')->where('grouping', '!=', '')->orderBy('grouping')->pluck('grouping')->unique();
        // $groupings = Code::whereIn('code_id', $groupingids)->pluck('category', 'code_id');
        $groupings = Code::whereIn('code_id', $groupingids)->whereHas('get_category')->with('get_category')->get();
        $map = Map::find(1);
        $code_selects = Code::whereNotNull('code')->where('code', '!=', '')->pluck('code', 'code')->unique();

        $code_system = Code::whereNotNull('code_system')->where('code_system', '!=', '')->pluck('code_system', 'code_system')->unique();
        if (!$request->ajax()) {
            return view('frontEnd.terminology.index', compact('category', 'resource', 'resource_element', 'code_system', 'groupings', 'map', 'domain', 'code_selected', 'code_selects'));
        }
        $codes = Code::select('*');

        return DataTables::of($codes)
            ->addColumn('action', function ($row) {
                $links = '';
                if ($row) {
                    $links .= '<a href="' . route("codes.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                    $id = $row->id;
                    $route = 'codes';
                    $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                }
                return $links;
            })
            ->editColumn('code', function ($row) {
                $links = '';
                if ($row) {
                    $id = json_encode([$row->id]);
                    if ($row->code != 'None') {
                        $links .= '<a href="/search?find=&search_address=&lat=&long=&meta_status=On&paginate=10&sort=&target_all=&pdf=&csv=&filter_label=&organization_tags=&service_tags=&sdoh_codes_category=&sdoh_codes_data=' . $id . '&selected_taxonomies=">' . $row->code . '</a>';
                    } else {
                        $links = 'None';
                    }
                }
                return $links;
            })
            ->editColumn('grouping', function ($row) {
                $code = Code::where('code_id', $row->grouping)->first();
                return $code && $code->get_category ? $code->get_category->name : '';
            })
            ->editColumn('category', function ($row) {
                $links = '';
                if ($row) {
                    $category = json_encode([$row->category]);

                    $links .= "<a href='/search?find=&search_address=&lat=&long=&meta_status=On&paginate=10&sort=&target_all=&pdf=&csv=&filter_label=&organization_tags=&service_tags=&sdoh_codes_category=" . $category . "&sdoh_codes_data=&selected_taxonomies='>" . $row->get_category->name . "</a>";
                }
                return $links;
            })
            ->addColumn('services', function ($row) {
                $name = '';
                if ($row->code_ledger && count($row->code_ledger) > 0) {
                    foreach ($row->code_ledger as $key => $value) {

                        $name .= ($key != 0 ? ', ' : '') . ($value->service ? $value->service->service_name : '') . ' - ' . ($value->organization ? $value->organization->organization_name : '') . ' - ' . $value->rating;
                    }
                } else {
                    $services = Service::where('procedure_grouping', 'LIKE', '%' . $row->code_id . '%')->get();
                    foreach ($services as $key => $value) {
                        $name .= ($key != 0 ? ', ' : '') . ($value ? $value->service_name : '') . ' - ' . ($value->organizations ? $value->organizations->organization_name : '');
                    }
                }
                return $name;
            })
            ->filter(function ($query) use ($request) {
                $extraData = $request->get('extraData');

                if ($extraData) {

                    if (isset($extraData['category']) && $extraData['category'] != null) {
                        $query = $query->whereIn('category', $extraData['category']);
                    }
                    if (isset($extraData['code']) && $extraData['code'] != null) {
                        $query = $query->whereIn('code', $extraData['code']);
                    }
                    if (isset($extraData['resource']) && $extraData['resource'] != null) {
                        $query = $query->whereIn('resource', $extraData['resource']);
                    }
                    if (isset($extraData['resource_element']) && $extraData['resource_element'] != null) {
                        $query = $query->whereIn('resource_element', $extraData['resource_element']);
                    }
                    if (isset($extraData['grouping']) && $extraData['grouping'] != null) {
                        $query = $query->whereIn('grouping', $extraData['grouping']);
                    }
                    if (isset($extraData['code_selects']) && $extraData['code_selects'] != null) {
                        $query = $query->whereIn('code', $extraData['code_selects']);
                    }
                    if (isset($extraData['code_system']) && $extraData['code_system'] != null) {
                        $query = $query->whereIn('code_system', $extraData['code_system']);
                    }
                    if (isset($extraData['code_with_service']) && $extraData['code_with_service'] != null && $extraData['code_with_service'] == "true") {
                        $code_ids = CodeLedger::whereNotNull('service_recordid')->pluck('SDOH_code')->toArray();
                        $query = $query->whereIn('id', $code_ids);
                    }
                }
                return $query;
            }, true)
            ->rawColumns(['action', 'services', 'code', 'category'])
            ->make(true);
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
        //
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
