<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;

class InvitationController extends Controller
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
            $data = Invitation::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    $btn = '';
                    if (isset($row->user)) {
                        $btn = $row->user->name;
                    }
                    return $btn;
                })
                ->addColumn('invitation_date', function ($row) {
                    $btn = '';
                    if (isset($row->invitation_date)) {
                        $btn = date('d-m-Y h:i A', strtotime($row->invitation_date));
                    }
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $statusToggleBtn = '';
                    $viewBtn = '';
                    if (isset($row)) {
                        $viewBtn = '<a title="VIEW" href="' . route('admin.invitation.show', ['invitation' => $row->id]) . '" class="btn btn-secondary btn-sm"><i class="fa fa-info-circle"></i> ' . __('View') . ' </a> ';
                        if (Auth::user()->checkAdminAccess('invitation', null, 'update')) {
                            $statusLabel = (isset($row->status) && $row->status == 1) ? __('InActive') : __('Active');
                            $statusTitle = (isset($row->status) && $row->status == 1) ? __('InActive') : __('Active');
                            $statusClass = (isset($row->status) && $row->status == 1) ? 'btn-danger' : 'btn-success';
                            $statusToggleBtn = '<a class="btn ' . $statusClass . ' btn-sm" title="' . $statusTitle . '" href="' . route('admin.invitation.status.toggle', ['invitation' => $row->id]) . '">' . $statusTitle . '</a> ';
                        }
                    }
                    return $statusToggleBtn. $viewBtn ;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.invitation.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function show(Invitation $invitation)
    {
        if ($invitation) {
            return view('admin.invitation.show',compact('invitation'));
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(Invitation $invitation)
    {
        if ($invitation) {
            $customer=$invitation->user;
            $name = $customer->name;
            $status = ($invitation->status)?'InActivated':'Activated';
            $invitation->status = !$invitation->status;
            $invitation->save();
            return redirect()->route('admin.invitation.index')->with('toast-success', $name.' '.__('Successfully '.$status).'!');
        }
        return redirect()->back()->with('toast-error', 'Customer not found!');
    }
}
