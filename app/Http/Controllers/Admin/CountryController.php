<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CountryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('checkStatusAdmin');
        $this->middleware('CheckAdminAccess');
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $countries = Country::get();
        return view('admin.country.index',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $this->validate($req, [
            'name' => 'required|string|max:255',
            //'ar_name' => 'required|string|max:255',
        ]);
        $data = $req->all();
        $countryObj = new Country;
        $country = $countryObj->createCountry($data);
        return redirect()->route('admin.country.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        if ($country) {
            return view('admin.country.show',compact('country'));
        }
        return redirect()->back()->with('toast-error', 'Country not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        if ($country) {
            return view('admin.country.edit',compact('country'));
        }
        return redirect()->back()->with('toast-error', 'Country not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'name' => 'required|string|max:255',
            //'ar_name' => 'required|string|max:255',
        ]);
        $data = $req->all();
        $country = Country::find($id);
        if ($country) {
            $country = $country->updateCountry($data,$country);
            return redirect()->route('admin.country.index')->with('toast-success', __('Successfully added').'!');
        }
        return redirect()->back()->with('toast-error', 'Country not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $name = $country->name;
        $country->delete();
        return redirect()->route('admin.country.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
