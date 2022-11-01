<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use DateTime;
class AdsController extends Controller
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
        if ($this->authUser->type == 0){
            $ads = Ads::all();
        }else{
            $ads=Ads::where('admin_id',$this->authUser->id)->get();
        }
        return view('admin.ads.index',compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d');
        if ($this->authUser->type == 0){
            $items=Item::all();
        }else{
            $items=Item::where('admin_id',$this->authUser->id)->get();
        }
        return view('admin.ads.create',compact('endDate','startDate','items'));
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
            'title'   => 'required|string|max:255',
            'target'   => 'required|string|in:in,out',
            'description'   => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10000',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            //'status' => 'required|integer'
        ]);
        $data = $req->all();
        $userObj = new User;
        if(isset($data['image'])){
            $data['image'] = $userObj->uploadFile($data['image']);
        }
        $adsObj = new Ads;
        $data['admin_id']=Auth::user()->id;
        $data['status']=0;
        $ads = $adsObj->createAds($data);
        return redirect()->route('admin.ads.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function show(Ads $ads)
    {
        if ($ads) {
            return view('admin.ads.show',compact('ads'));
        }
        return redirect()->back()->with('toast-error', 'Ads not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function edit(Ads $ads)
    {
        if ($ads) {
            $startDate = $ads->start_at?$ads->start_at:date('Y-m-d');
            $endDate =$ads->end_at?$ads->end_at:date('Y-m-d');
            if ($this->authUser->type == 0){
                $items=Item::all();
            }else{
                $items=Item::where('admin_id',$this->authUser->id)->get();
            }
            return view('admin.ads.edit',compact('ads','endDate','startDate','items'));
        }
        return redirect()->back()->with('toast-error', 'Ads not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'title'   => 'nullable|string|max:255',
            'title_en'   => 'nullable|string|max:255',
            'restaurant_id'   => 'nullable|integer',
            'target'   => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'start_at' => 'nullable|date|date_format:Y-m-d',
            'end_at' => 'nullable|date|date_format:Y-m-d',
            'status' => 'nullable|string'
        ]);
        $data = $req->all();
        $userObj = new User;
        $ads = Ads::find($id);
        if ($ads) {
                if(isset($data['image'])){
                    $data['image'] = $userObj->uploadFile($data['image']);
                }
            $data['admin_id']=Auth::user()->id;
            $ads = $ads->updateAds($data,$ads);
            return redirect()->route('admin.ads.index')->with('toast-success', __('Successfully updated').'!');
        }
        return redirect()->back()->with('toast-error', 'Ads not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(Ads $ads)
    {

        if ($ads->status == 1){
            $ads->status=0;
        }else{
            $ads->status=1;
        }
        $ads->save();
        return redirect()->route('admin.ads.index')->with('toast-success', __('Successfully Updated').'!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ads $ads)
    {
        $name = $ads->name;
        $ads->delete();
        return redirect()->route('admin.ads.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
