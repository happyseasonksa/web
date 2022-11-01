<?php

namespace App\Http\Controllers\Admin;

use App\Models\ImageCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ImageCategoryController extends Controller
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
        $categorys = ImageCategory::get();
        return view('admin.image_category.index',compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.image_category.create');
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
            'status' => 'required|string|in:false,true'
        ]);
        $data = $req->all();
        $categoryObj = new ImageCategory;
        $category = $categoryObj->createImageCategory($data);
        return redirect()->route('admin.image-category.index')->with('toast-success', __('Successfully added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show(ImageCategory $category)
    {
        if ($category) {
            return view('admin.image_category.show',compact('category'));
        }
        return redirect()->back()->with('toast-error', 'Image Category not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImageCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(ImageCategory $category)
    {
        if ($category) {
            return view('admin.image_category.edit',compact('category'));
        }
        return redirect()->back()->with('toast-error', 'Image Category not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImageCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:false,true'
        ]);
        $data = $req->all();
        $category = ImageCategory::find($id);
        if ($category) {
            $category = $category->updateImageCategory($data,$category);
            return redirect()->route('admin.image-category.index')->with('toast-success', __('Successfully updated'));
        }
        return redirect()->back()->with('toast-error', 'Image Category not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\ImageCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(ImageCategory $category)
    {
        $name = $category->name;
        $status = ($category->status)?'InActivated':'Activated';
        $category->status = !$category->status;
        $category->save();
        return redirect()->route('admin.image-category.index')->with('toast-success', $name." ".__('Successfully '.$status).'!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageCategory $category)
    {
        $name = $category->name;
        $category->delete();
        return redirect()->route('admin.image-category.index')->with('toast-success', $name." ".__('successfully deleted'));
    }
}
