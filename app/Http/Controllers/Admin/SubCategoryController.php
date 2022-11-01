<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SubCategoryController extends Controller
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
        $subcategorys = SubCategory::all();
        return view('admin.subcategory.index',compact('subcategorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subcategory.create');
    }

    public function getCategory(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        $categorys = [];
        try {
            if ($req->id) {
                $categorys =Category::where('status', 1)->get();
            }
            $res = [
                'status' => true,
                'data' => $categorys,
            ];
        } catch (\Exception $e) {
            // $res['message'] = $e->getMessage();
        }
        return response()->json($res);
    }

    public function uploadCsv(Request $req, Restaurant $restaurant)
    {
        $this->validate($req, [
            'csv_file'   => 'required|mimes:csv,txt|max:10000',
        ]);
        $file = csvToArray($req->csv_file);
        $subCategoryObj = new SubCategory;
        if ($file) {
            if (count($file) > 0) {
                if (!isset($req->category_id)) {
                    return redirect()->back()->with('toast-error', 'Invalid category selected!');
                }
                $category = Category::find($req->category_id);
                if (!$category) {
                    return redirect()->back()->with('toast-error', 'Invalid category selected!');
                }
                foreach ($file as $item) {
                    if (isset($item['name'],$item['arabic_name'],$item['status']) && strlen($item['name']) < 255 && strlen($item['arabic_name']) < 255) {
                        $data = [
                            'restaurant_id' => $restaurant->id,
                            'category_id' => $category->id,
                            'name' => $item['name'],
                            'ar_name' => $item['arabic_name'],
                            'status' => (strtolower($item['status']) == 'active')?'true':'false',
                        ];
                        $subCategory = $subCategoryObj->createSubCategory($data);
                    }
                }
                return redirect()->route('admin.sub-category.index',['restaurant_id'=>$restaurant->id])->with('toast-success', 'Successfully uploaded csv!');
            }
        }
        return redirect()->back()->with('toast-error', 'Invalid csv uploaded!');
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
            'category_id' => 'required|integer',
            'name'   => 'required|string|max:255',
            //'image' => 'image|mimes:jpeg,png,jpg|max:10000',
            'status' => 'required|string'
        ]);
        $data = $req->all();
        $subcategoryObj = new subcategory;
        $userObj = new User;
//        if($req->hasFile('image')){
//            $data['image'] = $userObj->uploadFile($req->image);
//        }
        $subcategory = $subcategoryObj->createsubcategory($data);
        return redirect()->route('admin.sub-category.index')->with('toast-success', __('Successfully added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(subcategory $subcategory)
    {
        if ($subcategory) {
            $categorys = Category::where('status', true)->get();
            return view('admin.subcategory.edit',compact('subcategory','categorys'));
        }
        return redirect()->back()->with('toast-error', 'subcategory not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'category_id' => 'required|integer',
            'name'   => 'required|string|max:255',
            'status' => 'required|string',
           // 'image' => 'image|mimes:jpeg,png,jpg|max:10000',

        ]);
        $data = $req->all();
        $subcategory = SubCategory::find($id);
        if ($subcategory) {
            $userObj = new User;
//            if($req->hasFile('image')){
//                $data['image'] = $userObj->uploadFile($req->image);
//            }
            $subcategory = $subcategory->updatesubcategory($data,$subcategory);
            return redirect()->route('admin.sub-category.index')->with('toast-success', __('Successfully updated'));
        }
        return redirect()->back()->with('toast-error', 'subcategory not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(subcategory $subcategory)
    {

        $name = $subcategory->name;
        $status = ($subcategory->status)?'InActivated':'Activated';
        $subcategory->status = !$subcategory->status;
        $subcategory->save();
        return redirect()->route('admin.sub-category.index')->with('toast-success', $name.__('Successfully '.$status).'!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(subcategory $subcategory)
    {
        $name = $subcategory->name;
        $subcategory->delete();
        return redirect()->route('admin.sub-category.index')->with('toast-success', $name.__('successfully deleted').'!');
    }
}
