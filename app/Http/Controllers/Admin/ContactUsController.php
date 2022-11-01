<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;

class ContactUsController extends Controller
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
            $data = ContactUs::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                    $btn = '';
                    if (isset($row->created_at)) {
                        $btn = date('d-m-Y h:i A',strtotime($row->created_at));
                    }
                    return $btn;
                })
                ->addColumn('reply', function($row){
                    return $row->reply??'';
                })
                ->addColumn('action', function($row){
                    if (!isset($row->reply)) {
                        return '<div class="d-flex"> <div class="px-0"><a class="btn btn-info btn-sm d-flex align-items-center mr-2" title="Reply" href="' . route('admin.contactUs.edit', ["contactUs" => $row->id]) . '"><i class="fa fa-info-circle mr-2"></i>' . __("Reply") . ' </a></div>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.contactUs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactUs $contactUs)
    {
        if ($contactUs) {

            return view('admin.contactUs.edit',compact('contactUs'));
        }
        return redirect()->back()->with('toast-error', 'ContactUs not found!');
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
            'reply'   => 'required|string|max:255',
        ]);
        $data = $req->all();
        $ads = ContactUs::find($id);
        if ($ads) {
            $data['admin_id']=Auth::user()->id;
            $ads->update($data);
            return redirect()->route('admin.contactUs.index')->with('toast-success', 'Successfully reply!');
        }
        return redirect()->back()->with('toast-error', 'contactUs not found!');
    }

}
