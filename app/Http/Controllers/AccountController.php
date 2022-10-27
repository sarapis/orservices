<?php

namespace App\Http\Controllers;

use App\Model\AccountPage;
use App\Model\Layout;
use App\Model\Map;
use App\Model\Organization;
use App\Model\Role;
use App\Model\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $account = AccountPage::whereId(1)->first();
        $account->appear_for = $account->appear_for ? unserialize($account->appear_for) : null;
        $account->sidebar_widget = $account->sidebar_widget ? unserialize($account->sidebar_widget) : null;


        $roles = Role::get();
        return view('backEnd.account.index', compact('account', 'roles'));
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
        try {
            $account = AccountPage::whereId(1)->first();
            if ($account) {
                $account->top_content = $request->top_content;
                $account->sidebar_widget = serialize($request->sidebar_widget);
                $account->appear_for = serialize($request->appear_for);
                $account->save();
            } else {
                $account = new AccountPage();
                $account->top_content = $request->top_content;
                $account->sidebar_widget = serialize($request->sidebar_widget);
                $account->appear_for = serialize($request->appear_for);
                $account->save();
            }
            Session::flash('message', 'Account updated successfully!');
            Session::flash('status', 'success');
            return redirect('account');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('account');
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
        $layout = Layout::first();
        $map = Map::find(1);
        $user = Auth::user();
        $user = User::whereId(Auth::id())->first();
        $account = AccountPage::whereId(1)->first();
        $account->appear_for = $account->appear_for ? unserialize($account->appear_for) : null;
        $account->sidebar_widget = $account->sidebar_widget ? unserialize($account->sidebar_widget) : null;
        // $user_organizationid_list = $user->user_organization ? explode(',', $user->user_organization) : [];
        $organization_id_list = [];
        $service_id_list = [];
        $organization_list = [];
        $service_list = [];
        if ($user && $user->roles && $user->roles->name == 'Network Admin') {
            $organization_tags = explode(',', $user->organization_tags);
            foreach ($organization_tags as $key => $value) {
                $organizations = Organization::where('organization_tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $organization_id_list = array_unique(array_merge($organization_id_list, $organizations));
            }
            $organization_list = Organization::whereIn('id', $organization_id_list)->paginate(5);
        } elseif ($user && $user->roles && ($user->organization_tags || $user->service_tags)) {
            $organization_tags = explode(',', $user->organization_tags);
            foreach ($organization_tags as $key => $value) {
                $organizations = Organization::where('organization_tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $organization_id_list = array_unique(array_merge($organization_id_list, $organizations));
            }
            $organization_list = Organization::whereIn('id', $organization_id_list)->paginate(5);

            $service_tags = explode(',', $user->service_tags);
            foreach ($service_tags as $key => $value) {
                $services = Service::where('service_tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $service_id_list = array_unique(array_merge($service_id_list, $services));
            }
            $service_list = Service::whereIn('id', $service_id_list)->paginate(5);
        } else {
            $organization_list = $user->organizations()->paginate(5);
        }
        // $organization_list = Organization::whereIn('organization_recordid', $user_organizationid_list)->orderby('organization_recordid')->get();
        return view('frontEnd.account.account', compact('map', 'user', 'organization_list', 'layout', 'account', 'service_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $map = Map::find(1);
        $user_info = User::where('id', '=', $id)->first();
        $organization_list = Organization::select('organization_recordid', 'organization_name')->get();
        $account_organization_list = explode(',', $user_info->user_organization);

        return view('frontEnd.account.edit', compact('user_info', 'map', 'organization_list', 'account_organization_list'));
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
        $user = User::find($id);
        if ($request->first_name) {
            $user->first_name = $request->first_name;
        }
        if ($request->last_name) {
            $user->last_name = $request->last_name;
        }
        if ($request->account_email) {
            $user->email = $request->account_email;
        }

        // if ($request->account_organizations) {
        //     $user->user_organization = join(',', $request->account_organizations);
        // } else {
        //     $user->user_organization = '';
        // }

        $user->save();

        // $user->organizations()->sync($request->account_organizations);

        return redirect('account/' . $id);
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
    public function change_password($id)
    {
        $map = Map::find(1);
        $user_info = User::where('id', '=', $id)->first();

        return view('frontEnd.account.change_password', compact('user_info', 'map'));
    }

    public function update_password(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        try {
            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
            Session::flash('message', 'Password changed successfully!');
            Session::flash('status', 'success');
            return redirect('account/' . $id);
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('account/' . $id . '/change_password');
        }
    }
    public function fetch_organization(Request $request)
    {
        try {
            $user = Auth::user();
            $organization_id_list = [];
            $query = $request->searchData;
            $organization_tags = $user->organization_tags ? explode(',', $user->organization_tags) : [];
            foreach ($organization_tags as $key => $value) {
                $organizations = Organization::where('organization_tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $organization_id_list = array_unique(array_merge($organization_id_list, $organizations));
            }
            if ($user->user_organization) {
                $user_organization = $user->user_organization ? explode(',', $user->user_organization) : [];
                foreach ($user_organization as $key => $value) {
                    $organizations = Organization::where('organization_recordid', $value)->pluck('id')->toArray();
                    $organization_id_list = array_unique(array_merge($organization_id_list, $organizations));
                }
            }
            $organization_list = Organization::whereIn('id', $organization_id_list);

            if ($query) {
                $organization_list = $organization_list->where('organization_name', 'like', '%' . $query . '%');
            }
            $organization_list = $organization_list->paginate(5);
            return response()->json([
                'data' => view('frontEnd.account.organizationData', compact('organization_list'))->render(),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
    public function fetch_account_service(Request $request)
    {
        try {
            $user = Auth::user();
            $service_id_list = [];
            $query = $request->searchServiceData;

            $service_tags = explode(',', $user->service_tags);
            foreach ($service_tags as $key => $value) {
                $services = Service::where('service_tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $service_id_list = array_unique(array_merge($service_id_list, $services));
            }
            $service_list = Service::whereIn('id', $service_id_list);

            if ($query) {
                $service_list = $service_list->where('service_name', 'like', '%' . $query . '%');
            }
            $service_list = $service_list->paginate(5);
            return response()->json([
                'data' => view('frontEnd.account.serviceData', compact('service_list'))->render(),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
}
