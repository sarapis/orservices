<?php

namespace App\Http\Controllers;

use App\Model\Layout;
use App\Model\Map;
use App\Model\Organization;
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
        //
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
        $layout = Layout::first();
        $map = Map::find(1);
        $user = Auth::user();
        $user_organizationid_list = $user->user_organization ? explode(',', $user->user_organization) : [];
        $organization_list = Organization::whereIn('organization_recordid', $user_organizationid_list)->select('organization_recordid', 'organization_name')->orderby('organization_recordid')->get();
        return view('frontEnd.account.account', compact('map', 'user', 'organization_list', 'layout'));

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
            Session::flash('status', 'error');
            return redirect('account/' . $id);

        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('account/' . $id . '/change_password');
        }

    }
}
