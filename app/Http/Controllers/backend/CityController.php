<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::cursor();
        return view('backEnd.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.cities.create');
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
            'city' => 'required|unique:cities,city'
        ]);
        try {
            City::create([
                'city' => $request->city
            ]);
            DB::commit();
            return redirect()->to('cities')->with('success', 'City created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->to('cities')->with('error', $th->getMessage());
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
        $city = City::whereId($id)->first();
        return view('backEnd.cities.edit', compact('city'));
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
            'city' => 'required|unique:cities,city'
        ]);
        try {
            City::whereId($id)->update([
                'city' => $request->city
            ]);
            DB::commit();
            return redirect()->to('cities')->with('success', 'City updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('cities')->with('error', $th->getMessage());
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
            City::whereId($id)->delete();

            return redirect()->to('cities')->with('success', 'City Deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('cities')->with('error', $th->getMessage());
        }
    }
    public function add_city()
    {
        try {
            $addresses = Address::cursor();
            foreach ($addresses as $key => $value) {
                if ($value->address_city) {
                    $city = City::where('city', rtrim(ltrim($value->address_city)))->exists();
                    if (!$city)
                        City::create(['city' => rtrim(ltrim($value->address_city))]);
                }
            }
            return redirect('/cities');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
