<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerAllergy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Auth;

class CustomerController extends Controller
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
        $this->middleware('CheckAdminAccess',['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $customers = collect();
        if ($req->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    $btn = '';
                    if (isset($row->name)) {
                        $btn = $row->name;
                    }
                    return $btn;
                })
//                ->addColumn('email', function($row){
//                    $btn = '';
//                    if (isset($row->email)) {
//                        $btn = $row->email;
//                    }
//                    return $btn;
//                })
                ->addColumn('phone', function($row){
                    $btn = '';
                    if (isset($row->phone)) {
                        $btn = $row->phone;
                        if (isset($row->phone_code)) {
                            $btn = $row->phone_code.$btn;
                        }
                    }
                    return $btn;
                })
                ->addColumn('status', function($row){
                    return ($row && $row->status)?__('InActive'):__('Active');
                })
                ->addColumn('status_show', function($row){
                    $btn = '<span class="badge badge-danger">'.__('InActive').'</span>';
                    if($row && $row->status){
                        $btn ='<span class="badge badge-success">'.__('Active').'</span>';
                    }
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $statusToggleBtn = '';
                    $editBtn = '';
                    $viewBtn = '';
                    $deleteBtn = '';
                    if (isset($row)) {
                        $viewBtn = '<a title="VIEW" href="'.route('admin.customer.show',['customer'=>$row->id]).'" class="btn btn-secondary btn-sm"><i class="fa fa-info-circle"></i> '.__('View').' </a> ';
                        if (Auth::user()->checkAdminAccess('customer', null, 'update')) {
                            $statusLabel = (isset($row->status) && $row->status == 1)?__('InActive'):__('Active');
                            $statusTitle = (isset($row->status) && $row->status == 1)?__('InActive'):__('Active');
                            $statusClass = (isset($row->status) && $row->status == 1)?'btn-danger':'btn-success';
                            $statusToggleBtn = '<a class="btn '.$statusClass.' btn-sm" title="'.$statusTitle.'" href="'.route('admin.customer.status.toggle',['customer'=>$row->id]).'">'.$statusTitle.'</a> ';
                            $editBtn = '<a class="btn btn-info btn-sm" title="EDIT" href="'.route('admin.customer.edit', ['customer' => $row->id]).'"><i class="fa fa-edit"></i> '.__('Edit').' </a> ';
                        }
                        if(Auth::user()->checkAdminAccess('customer', null, 'delete')){
                            $deleteBtn = '<a class="btn btn-danger btn-sm" title="DELETE" href="'.route('admin.customer.destroy', ['customer' => $row->id]).'"><i class="fa fa-trash"></i> '.__('Delete').' </a> ';
                        }
                    }
                    return $statusToggleBtn.$editBtn.$viewBtn.$deleteBtn;
                })
                ->rawColumns(['action','status_show'])
                ->make(true);
        }
        return view('admin.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customer.create');
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
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:7|max:15|unique:users',
             'password' => 'required|confirmed|min:6',
            // 'address' => 'required|string|max:255',
        ]);
        $data = $req->all();
        $userObj = new User;
        $data['address'] = null;
        $user = $userObj->createUser($data, 1);
        return redirect()->route('admin.customer.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(User $customer)
    {
        if ($customer) {
            return view('admin.customer.show',compact('customer'));
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(User $customer)
    {
        if ($customer) {
            return view('admin.customer.edit',compact('customer'));
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $customer = User::find($id);
        if ($customer) {
            $this->validate($req, [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|min:7|max:15|unique:users,phone,'.$customer->id,
                // 'address' => 'required|string|max:255',
            ]);
            $data = $req->all();
            $userObj = new User;
            $user = $customer;
            if (!$user) {
                return redirect()->back()->with('toast-error', 'Customer user is deleted!');
            }
            $user = $user->updateUser($data, $user);

            return redirect()->route('admin.customer.index')->with('toast-success', __('Successfully added').'!');
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(User $customer)
    {
        if ($customer) {
            $name = $customer->name;
            $status = ($customer->status)?'InActivated':'Activated';
            $customer->status = !$customer->status;
            $customer->save();
            return redirect()->route('admin.customer.index')->with('toast-success', $name.' '.__('Successfully '.$status).'!');
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer)
    {   $name = '';
        if ($customer) {
            $name = $customer->name;
            //delete user
            $customer->delete();
        }
        $customer->delete();
        return redirect()->route('admin.customer.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
