<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\Contact;
use App\Model\Location;
use App\Model\Map;
use App\Model\Organization;
use App\Model\Phone;
use App\Model\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use OwenIt\Auditing\Models\Audit;

class EditChangeController extends Controller
{
    public function __construct(CommonController $commonController)
    {
        $this->commonController = $commonController;
    }
    public function viewChanges(Request $request, $id, $recordid)
    {
        $audit = Audit::whereId($id)->first();
        $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
        $map = Map::find(1);
        $datas = $audit->auditable_type::where(strtolower($modal) . '_recordid', $recordid)->first()->toArray();
        // dd($datas);

        return view('frontEnd.viewChanges', compact('audit', 'map', 'modal', 'datas', 'id', 'recordid'));
    }
    public function getDatas(Request $request, $id, $recordid)
    {
        try {
            $audit = Audit::whereId($id)->first();

            // $allAudit = Audit::where('auditable_id', $recordid)->with('user')->get();
            $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
            $recordData = $audit->auditable_type::where(strtolower($modal) . '_recordid', $recordid)->first();
            $sectionName = $modal . 'Section';
            $allAudit = $this->commonController->$sectionName($recordData);
            foreach ($allAudit as $key => $value) {
                if ($id == $value->id)
                    $audit = $value;
            }

            // $allAudit->filter(function ($value) use ($modal, $audit) {
            //     $new_values = $value->new_values;
            //     $old_values = $value->old_values;
            //     foreach ($value->new_values as $key => $item) {
            //         // service phone section
            //         if ($key == strtolower($modal) . '_phones') {
            //             $servicePhoneIds = explode(',', $value->new_values[$key]);
            //             $servicePhoneOldIds = explode(',', $value->old_values[$key]);

            //             $servicePhoneIds = array_filter($servicePhoneIds);
            //             $servicePhoneOldIds = array_filter($servicePhoneOldIds);
            //             $phoneNumbers = '';
            //             $oldPhoneNumbers = '';

            //             foreach ($servicePhoneIds as $keyP => $valueP) {
            //                 $phoneData = Phone::where('phone_recordid', $valueP)->first();
            //                 if ($phoneData) {
            //                     if ($keyP == 0)
            //                         $phoneNumbers = $phoneData->phone_number;
            //                     else
            //                         $phoneNumbers = $phoneNumbers . ', ' . $phoneData->phone_number;
            //                 }
            //             }
            //             $new_values[$key] = $phoneNumbers;

            //             foreach ($servicePhoneOldIds as $keyO => $valueO) {
            //                 $oldPhoneData = Phone::where('phone_recordid', $valueO)->first();
            //                 if ($oldPhoneData) {
            //                     if ($keyO == 0)
            //                         $oldPhoneNumbers = $oldPhoneData->phone_number;
            //                     else
            //                         $oldPhoneNumbers = $oldPhoneNumbers . ', ' . $oldPhoneData->phone_number;
            //                 }
            //             }
            //             $old_values[$key] = $oldPhoneNumbers;
            //         }
            //     }
            //     $value->new_values = $new_values;
            //     $value->old_values = $old_values;

            //     return true;
            // });


            $datas = $audit->auditable_type::where(strtolower($modal) . '_recordid', $recordid)->first()->toArray();

            $datas = $this->commonController->getSingleData($datas, $modal);


            $map = Map::find(1);
            if ($modal == 'Location') {
                $route = '/facilities/' . $recordid;
            } else {
                $route = '/' . strtolower($modal) . 's/' . $recordid;
            }
            return response()->json([
                'datas' => $datas,
                'audit' => $audit,
                'allAudit' => $allAudit,
                'route' => $route,
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
    public function restoreDatas(Request $request, $id, $recordid)
    {
        try {
            $modalFull = $request->modal;
            $audit = Audit::whereId($id)->first();
            $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
            if ($audit && $audit->new_values) {
                // $newData = new $modal;
                $temp = [];
                foreach ($audit->new_values as $key => $value) {
                    $temp[$key] = $value;
                }
                $route = '/';
                if ($modal == 'Organization') {
                    $newData = Organization::where(strtolower($modal) . '_recordid', $recordid)->first();
                    $route = '/organizations/' . $recordid;
                } else if ($modal == 'Contact') {
                    $newData = Contact::where(strtolower($modal) . '_recordid', $recordid)->first();
                    $route = '/contacts/' . $recordid;
                } else if ($modal == 'Service') {
                    $newData = Service::where(strtolower($modal) . '_recordid', $recordid)->first();
                    $route = '/services/' . $recordid;
                } else {
                    $newData = Location::where(strtolower($modal) . '_recordid', $recordid)->first();
                    $route = '/facilities/' . $recordid;
                }
                foreach ($audit->new_values as $key => $value) {
                    $newData->$key = $value;
                }
                $newData->save();
                // $newData->save();
                $auditLatest = Audit::whereId($id)->latest();
                $allAudit = Audit::where('auditable_id', $recordid)->get();
                $datas = $audit->auditable_type::where(strtolower($modal) . '_recordid', $recordid)->first()->toArray();
                Session::flash('message', 'Restored successfully!');
                Session::flash('status', 'success');

                return response()->json([
                    'audit' => $auditLatest,
                    'allAudit' => $allAudit,
                    'datas' => $datas,
                    'route' => $route,
                    'message' => 'Restored successfully!',
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Something went wrong!',
                    'success' => false
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
