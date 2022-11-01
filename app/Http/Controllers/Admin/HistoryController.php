<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use App\Models\ContactHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;

class HistoryController extends Controller
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
        if ($req->ajax()) {
            $data = ContactHistory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    $btn = '';
                    if (isset($row->user)) {
                        $btn = $row->user->name;
                    }
                    return $btn;
                })
                ->addColumn('admin', function($row){
                    $btn = '';
                    if (isset($row->admin)) {
                        $btn = $row->admin->name;
                    }
                    return $btn;
                })
                ->addColumn('item', function($row){
                    $btn = '';
                    if (isset($row->item)) {
                        $btn = $row->item->name;
                    }
                    return $btn;
                })
                ->addColumn('contact', function($row){
                    $btn = '';
                    if (isset($row->contact_type)) {
                        $btn = $row->contact_type;
                    }
                    return $btn;
                })
                ->addColumn('creation_date', function ($row) {
                    $btn = '';
                    if (isset($row->created_at)) {
                        $btn = date('d-m-Y h:i A', strtotime($row->created_at));
                    }
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $statusToggleBtn = '';
                    $viewBtn = '';
                    if (isset($row)) {
                        if (Auth::user()->checkAdminAccess('invitation', null, 'update')) {
                            $viewBtn='<button class="btn btn-danger btn-sm" title="'.__('DELETE').'" onclick="confirmAlert(`'.route('admin.item.destroy', ['item' => $row]).'`)"><i class="fa fa-trash"></i>'.__('Delete').'</button>';
                        }
                    }
                    return  $viewBtn ;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.history.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactHistory  $history
     * @return \Illuminate\Http\Response
     */
    public function show(ContactHistory $history)
    {
        if ($history) {
            return view('admin.history.show',compact('invitation'));
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\ContactHistory  $history
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(ContactHistory $history)
    {
        if ($history) {
            $customer=$history->user;
            $name = $customer->name;
            $status = ($history->status)?'InActivated':'Activated';
            $history->status = !$history->status;
            $history->save();
            return redirect()->route('admin.invitation.index')->with('toast-success', $name.' '.__('Successfully '.$status).'!');
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }
}
