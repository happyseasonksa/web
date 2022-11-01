<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;

use App\Models\RestaurantBranch;
use App\Models\AdminRestaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminController extends Controller
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
    public function index()
    {
        $admins = Admin::where('type','!=', 0)->where('id','!=',Auth::user()->id);
        if ($this->authUser->type === 1) {
            return redirect()->back()->with('toast-error', 'Unauthorized.');
        }
        $admins = $admins->get();
        return view('admin.access.index',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->authUser->type === 1) {
            return redirect()->back()->with('toast-error', 'Unauthorized.');
        }
        return view('admin.access.create');
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
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'status' => 'required|string',
            //'type' => 'required|string|integer',
        ]);
        if (($this->authUser->type === 1)) {
            return redirect()->back()->with('toast-error', 'Invalid admin selected!');
        }
        $data = $req->all();
        $data['type']=1;
        $adminObj = new Admin;
        $admin = $adminObj->createAdmin($data);
        // create Access
        $admin->defineAccess($admin,$data);
        return redirect()->route('admin.access.index')->with('toast-success', __('Successfully added').'!');
    }

    public function getBranch(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        $branchs = [];
        try {
            if ($req->id) {
                $restaurants = Restaurant::find($req->id);
                foreach ($restaurants as $key => $restaurant) {
                    $restBranchs = $restaurant->branchs()->where('status', 1)->get();
                    foreach ($restBranchs as $restBranch) {
                        $branchs[] = [
                            'id' => $restBranch->id,
                            'name' => $restaurant->name.' : '.$restBranch->name,
                        ];
                    }
                }
            }
            $res = [
                'status' => true,
                'data' => $branchs,
            ];
        } catch (\Exception $e) {
            // $res['message'] = $e->getMessage();
        }
        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customer  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        if ($admin) {
            if ($this->authUser->type === 1) {
                return redirect()->back()->with('toast-error', 'Unauthorized.');
            }
            return view('admin.access.show',compact('admin'));
        }
        return redirect()->back()->with('toast-error', 'Admin not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\customer  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //$authRestIds = $this->authUser->assignItems()->pluck('restaurant_id')->toArray();
        if ($admin) {
            if ($this->authUser->type === 1) {
                return redirect()->back()->with('toast-error', 'Unauthorized.');
            }
          //  $restaurants = getRestAsAdmin($this->authUser)->get();
            return view('admin.access.edit',compact('admin'));
        }
        return redirect()->back()->with('toast-error', 'Admin not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\customer  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        ;
        $admin = Admin::find($id);
        if ($admin) {
            if ($this->authUser->type === 1) {
                return redirect()->back()->with('toast-error', 'Unauthorized.');
            }
            $this->validate($req, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email,'.$admin->id,
                'phone' => 'required|string|max:255|unique:admins,phone,'.$admin->id,
                'status' => 'required|string',
            ]);
            $data = $req->all();
            $admin = $admin->updateAdmin($data,$admin);
            // create Access
            $admin->adminAccesses()->delete();
            $admin->defineAccess($admin,$data);

            return redirect()->route('admin.access.index')->with('toast-success', __('Successfully added').'!');
        }
        return redirect()->back()->with('toast-error', 'Admin not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\customer  $admin
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(Admin $admin)
    {
        if ($this->authUser->type === 1) {
            return redirect()->back()->with('toast-error', 'Unauthorized.');
        }
        $name = $admin->name;
        $status = ($admin->status)?'InActivated':'Activated';
        $admin->status = !$admin->status;
        $admin->save();
        return redirect()->route('admin.access.index')->with('toast-success', $name.' '.__('Successfully '.$status).'!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customer  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        if ($this->authUser->type === 1) {
            return redirect()->back()->with('toast-error', 'Unauthorized.');
        }
        $name = $admin->name;
        $admin->delete();
        return redirect()->route('admin.access.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
