<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CategoryController extends Controller
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
        $categorys = collect();
        $categorys = Category::all();
        return view('admin.category.index',compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.category.create');
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
            'status' => 'required|string',
            'icon' => 'image|mimes:jpeg,png,jpg|max:10000',

        ]);
        $data = $req->all();
        $categoryObj = new Category;
        $userObj = new User;
        if($req->hasFile('icon')){
            $data['icon'] = $userObj->uploadFile($req->icon);
        }
        $category = $categoryObj->createCategory($data);
        return redirect()->route('admin.category.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadCsv(Request $req, Restaurant $restaurant)
    {
        $this->validate($req, [
            'csv_file'   => 'required|mimes:csv,txt|max:10000',
        ]);
        $file = csvToArray($req->csv_file);
        $categoryObj = new Category;
        if ($file) {
            if (count($file) > 0) {
                foreach ($file as $item) {
                    if (isset($item['name'],$item['arabic_name'],$item['status']) && strlen($item['name']) < 255 && strlen($item['arabic_name']) < 255) {
                        $data = [
                            'name' => $item['name'],
                            'ar_name' => $item['arabic_name'],
                            'status' => (strtolower($item['status']) == 'active')?'true':'false',
                        ];
                        $category = $categoryObj->createCategory($data);
                    }
                }
                return redirect()->route('admin.category.index')->with('toast-success', 'Successfully uploaded csv!');
            }
        }
        return redirect()->back()->with('toast-error', 'Invalid csv uploaded!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        if ($category) {
            $subcategorys = $category->subcategories;
            return view('admin.category.show',compact('category','subcategorys'));
        }
        return redirect()->back()->with('toast-error', 'Category not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        if ($category) {
            return view('admin.category.edit',compact('category'));
        }
        return redirect()->back()->with('toast-error', 'Category not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'name'   => 'required|string|max:255',
            'status' => 'required|string',
            'icon' => 'image|mimes:jpeg,png,jpg|max:10000',
        ]);
        $data = $req->all();
        $category = Category::find($id);
        if ($category) {
            $userObj = new User;
            if($req->hasFile('icon')){
                $data['icon'] = $userObj->uploadFile($req->icon);
            }
            $category = $category->updatecategory($data,$category);
            return redirect()->route('admin.category.index')->with('toast-success', __('Successfully updated').'!');
        }
        return redirect()->back()->with('toast-error', 'Category not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(category $category)
    {
        $name = $category->name;
        $status = ($category->status)?'InActivated':'Activated';
        $category->status = !$category->status;
        $category->save();
        return redirect()->route('admin.category.index')->with('toast-success', $name.' '.__('Successfully '.$status).'!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        $name = $category->name;
        $category->delete();
        return redirect()->route('admin.category.index')->with('toast-success', $name.' '. __('successfully deleted').' ! ');
    }
}
