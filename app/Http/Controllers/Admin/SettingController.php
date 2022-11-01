<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
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
        $settings = Setting::get();
        return view('admin.setting.index',compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.setting.create');
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
            'name'   => 'required|string|max:255',
            'value'   => 'required|string|max:255',
        ]);
        $data = $req->all();
        $settingObj = new Setting();
        $setting = $settingObj->createSetting($data);
        return redirect()->route('admin.setting.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        if ($setting) {
            return view('admin.setting.show',compact('setting'));
        }
        return redirect()->back()->with('toast-error', 'Setting not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        if ($setting) {
            return view('admin.setting.edit',compact('setting'));
        }
        return redirect()->back()->with('toast-error', 'Setting not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'name'   => 'nullable|string|max:255',
            'value'   => 'required|string|max:255',
        ]);
        $data = $req->all();
        $setting = Setting::find($id);
        if ($setting) {
            $setting = $setting->updateSetting($data,$setting);
            return redirect()->route('admin.setting.index')->with('toast-success', __('Successfully added').'!');
        }
        return redirect()->back()->with('toast-error', 'Setting not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $name = $setting->name;
        $setting->delete();
        return redirect()->route('admin.setting.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
