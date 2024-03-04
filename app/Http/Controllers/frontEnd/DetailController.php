<?php

namespace App\Http\Controllers\frontEnd;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\Detail;
use App\Model\DetailType;
use App\Services\Stringtoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetailController extends Controller
{

    public function airtable($access_token, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info) {
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->access_token = $access_token;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Detail::truncate();
        $airtable = new Airtable(array(
            'access_token' => $access_token,
            'base' => $base_url,
        ));

        $request = $airtable->getContent('details');

        do {

            $response = $request->getResponse();

            $airtable_response = json_decode($response, true);

            foreach ($airtable_response['records'] as $record) {

                $detail = new Detail();
                $strtointclass = new Stringtoint();

                $detail->detail_recordid = $strtointclass->string_to_int($record['id']);
                $detail->detail_value = isset($record['fields']['value']) ? $record['fields']['value'] : null;
                $detail->detail_type = isset($record['fields']['Detail Type']) ? $record['fields']['Detail Type'] : null;
                $detail_type = isset($record['fields']['Detail Type']) ? $record['fields']['Detail Type'] : null;

                if ($detail_type) {
                    $check_detail_type = DetailType::where('type', $detail_type)->exists();
                    if (!$check_detail_type) {
                        DetailType::create([
                            'type' => $detail_type,
                            'created_by' => Auth::id()
                        ]);
                    }
                }

                $detail->detail_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                if (isset($record['fields']['organizations'])) {
                    $i = 0;
                    foreach ($record['fields']['organizations'] as $value) {

                        $detailorganization = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $detail->detail_organizations = $detail->detail_organizations . ',' . $detailorganization;
                        } else {
                            $detail->detail_organizations = $detailorganization;
                        }

                        $i++;
                    }
                }

                $detail->detail_services = isset($record['fields']['services']) ? implode(",", $record['fields']['services']) : null;

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services'] as $value) {

                        $detailservice = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $detail->detail_services = $detail->detail_services . ',' . $detailservice;
                        } else {
                            $detail->detail_services = $detailservice;
                        }

                        $i++;
                    }
                }

                if (isset($record['fields']['locations'])) {
                    $i = 0;
                    foreach ($record['fields']['locations'] as $value) {

                        $detaillocation = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $detail->detail_locations = $detail->detail_locations . ',' . $detaillocation;
                        } else {
                            $detail->detail_locations = $detaillocation;
                        }

                        $i++;
                    }
                }

                $detail->save();
            }
        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Details')->first();
        $airtable->records = Detail::count();
        $airtable->syncdate = $date;
        $airtable->save();
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

            // Detail::truncate();
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('x-details');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {

                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_detail = Detail::where('detail_recordid', $recordId)->where('detail_value', isset($record['fields']['x-value']) ? $record['fields']['x-value'] : null)->first();
                    if ($old_detail == null) {
                        $detail = new Detail();
                        $strtointclass = new Stringtoint();

                        $detail->detail_recordid = $strtointclass->string_to_int($record['id']);
                        $detail->detail_value = isset($record['fields']['x-value']) ? $record['fields']['x-value'] : null;
                        $detail->detail_type = isset($record['fields']['x-type']) ? $record['fields']['x-type'] : null;

                        $detail_type = isset($record['fields']['x-type']) ? $record['fields']['x-type'] : null;

                        if ($detail_type) {
                            $check_detail_type = DetailType::where('type', $detail_type)->exists();
                            if (!$check_detail_type) {
                                DetailType::create([
                                    'type' => $detail_type,
                                    'created_by' => Auth::id()
                                ]);
                                DB::commit();
                            }
                        }
                        $detail->detail_description = isset($record['fields']['x-description']) ? $record['fields']['x-description'] : null;
                        if (isset($record['fields']['x-organizations'])) {
                            $i = 0;
                            foreach ($record['fields']['x-organizations'] as $value) {

                                $detailorganization = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $detail->detail_organizations = $detail->detail_organizations . ',' . $detailorganization;
                                } else {
                                    $detail->detail_organizations = $detailorganization;
                                }

                                $i++;
                            }
                        }
                        if (isset($record['fields']['x-contacts'])) {
                            $i = 0;
                            foreach ($record['fields']['x-contacts'] as $value) {

                                $detailcontacts = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $detail->contacts = $detail->contacts . ',' . $detailcontacts;
                                } else {
                                    $detail->contacts = $detailcontacts;
                                }

                                $i++;
                            }
                        }
                        if (isset($record['fields']['phones'])) {
                            $i = 0;
                            foreach ($record['fields']['phones'] as $value) {

                                $detailphones = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $detail->phones = $detail->phones . ',' . $detailphones;
                                } else {
                                    $detail->phones = $detailphones;
                                }

                                $i++;
                            }
                        }

                        $detail->detail_services = isset($record['fields']['x-services']) ? implode(",", $record['fields']['x-services']) : null;

                        if (isset($record['fields']['x-services'])) {
                            $i = 0;
                            foreach ($record['fields']['x-services'] as $value) {

                                $detailservice = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $detail->detail_services = $detail->detail_services . ',' . $detailservice;
                                } else {
                                    $detail->detail_services = $detailservice;
                                }

                                $i++;
                            }
                        }

                        if (isset($record['fields']['x-locations'])) {
                            $i = 0;
                            foreach ($record['fields']['x-locations'] as $value) {

                                $detaillocation = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $detail->detail_locations = $detail->detail_locations . ',' . $detaillocation;
                                } else {
                                    $detail->detail_locations = $detaillocation;
                                }

                                $i++;
                            }
                        }

                        $detail->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'X_Details')->first();
            $airtable->records = Detail::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Detail: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details = Detail::orderBy('detail_value')->get();

        return view('backEnd.tables.tb_details', compact('details'));
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
        $detail = Detail::find($id);
        return response()->json($detail);
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
        $detail = Detail::find($id);
        $detail->detail_value = $request->detail_value;
        $detail->detail_type = $request->detail_type;
        $detail->detail_description = $request->detail_description;
        $detail->flag = 'modified';
        $detail->save();

        return response()->json($detail);
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
