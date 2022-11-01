<?php

namespace App\Http\Controllers\Admin;

use App\Models\Texts;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class TextsController extends Controller
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
        $texts = Texts::get();
        return view('admin.text.index',compact('texts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.text.create');
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
            'stext' => 'required|string|max:255',
        ]);
        $data = $req->all();
        $textObj = new Texts;
        $text = $textObj->createTexts($data);
        return redirect()->route('admin.text.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Texts  $text
     * @return \Illuminate\Http\Response
     */
    public function show(Texts $text)
    {
        if ($text) {
            return view('admin.text.show',compact('text'));
        }
        return redirect()->back()->with('toast-error', 'Texts not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Texts  $text
     * @return \Illuminate\Http\Response
     */
    public function edit(Texts $text)
    {
        if ($text) {
            return view('admin.text.edit',compact('text'));
        }
        return redirect()->back()->with('toast-error', 'Texts not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Texts  $text
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'stext' => 'required|string|max:255',

        ]);
        $data = $req->all();
        $text = Texts::find($id);
        if ($text) {
            $text = $text->updateTexts($data,$text);
            return redirect()->route('admin.text.index')->with('toast-success', __('Successfully added').'!');
        }
        return redirect()->back()->with('toast-error', 'Texts not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Texts  $text
     * @return \Illuminate\Http\Response
     */
    public function destroy(Texts $text)
    {
        $name = $text->name;
        $text->delete();
        return redirect()->route('admin.text.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
