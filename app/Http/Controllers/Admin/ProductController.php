<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemServices;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ProductController extends Controller
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
        $items = collect();
        $provider_id = $this->authUser->id;
        if ($provider_id && $this->authUser->type !== 0) {
            $items = Item::where('admin_id', $provider_id)->get();
        }else{
            $items=Item::all();
        }
        return view('admin.product.index',compact('items','provider_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.product.create');
    }

    public function getSubCategory(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        $cats = [];
        try {
            if ($req->id) {
                $category = Category::find($req->id);
                $cats = $category->subcategories()->where('status', 1)->get();
            }
            $res = [
                'status' => true,
                'data' => $cats,
            ];
        } catch (\Exception $e) {
            // $res['message'] = $e->getMessage();
        }
        return response()->json($res);
    }


    public function showImages(Request $req)
    {
        $res = [
            'status' => false,
            'message' => 'Something went wrong!',
        ];
        $images = [];
        try {
            if ($req->id) {
                $item = Item::find($req->id);
                if ($item) {
                    $images = $item->images;
                }
            }
            $res = [
                'status' => true,
                'data' => $images,
            ];
        } catch (\Exception $e) {
            // $res['message'] = $e->getMessage();
        }
        return response()->json($res);
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
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'address' => 'nullable|string',
            'openTimes' => 'nullable|string',
            'website' => 'nullable|string',
            'phone' => 'required|string',
            'facebook' => 'nullable|string',
            'twitter' => 'nullable|string',
            'instagram' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'status' => 'required|integer',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:10000'
        ]);
        $userObj = new User;
        $data = $req->all();
        $itemObj = new Item;
        $data['admin_id']=auth()->user()->id;
        $item = $itemObj->createItem($data);
        foreach ($req->images as $image) {
            $source = $userObj->uploadFile($image);
            ItemImage::createItemImage($source,$item->id);
        }
        foreach ($req->services as $service){
            if (isset($service)) {
                $newObj = new ItemServices();
                $dataRow = [];
                $dataRow['item_id'] = $item->id;
                $dataRow['admin_id'] = auth()->id();
                $dataRow['service'] = $service;
                $dataRow['service_type'] = 'services';
                $newObj->createService($dataRow);
            }
        }

        foreach ($req->staff as $service){
            if (isset($service)) {
                $newObj = new ItemServices();
                $dataRow = [];
                $dataRow['item_id'] = $item->id;
                $dataRow['admin_id'] = auth()->id();
                $dataRow['service'] = $service;
                $dataRow['service_type'] = 'staff';
                $newObj->createService($dataRow);
            }
        }

        foreach ($req->facilities as $service){
            if (isset($service)) {
                $newObj = new ItemServices();
                $dataRow = [];
                $dataRow['item_id'] = $item->id;
                $dataRow['admin_id'] = auth()->id();
                $dataRow['service'] = $service;
                $dataRow['service_type'] = 'facilities';
                $newObj->createService($dataRow);
            }
        }

        foreach ($req->offers as $service){
            if (isset($service)) {
                $newObj = new ItemServices();
                $dataRow = [];
                $dataRow['item_id'] = $item->id;
                $dataRow['admin_id'] = auth()->id();
                $dataRow['service'] = $service;
                $dataRow['service_type'] = 'offers';
                $newObj->createService($dataRow);
            }
        }


        return redirect()->route('admin.item.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        if ($this->authUser->id != $item->admin_id && $this->authUser->type != 0) {
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }
        $images=ItemImage::where('item_id',$item->id)->get();
        return view('admin.product.show',compact('item','images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        if ($this->authUser->id != $item->admin_id && $this->authUser->type != 0) {
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }


        return view('admin.product.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $data = $req->all();
        $item = Item::find($id);
        if ($item) {
            if ($this->authUser->id != $item->admin_id && $this->authUser->type != 0) {
                return redirect('/admin')->with('toast-error', __('Not Authorised'));
            }
            $rules = [
                'name'   => 'required|string|max:255',
                'category_id' => 'required|integer',
                'description' => 'required|string',
                'lat' => 'nullable|string',
                'lng' => 'nullable|string',
                'address' => 'nullable|string',
                'openTimes' => 'nullable|string',
                'website' => 'nullable|string',
                'phone' => 'required|string',
                'facebook' => 'nullable|string',
                'twitter' => 'nullable|string',
                'instagram' => 'nullable|string',
                'whatsapp' => 'nullable|string',
                'status' => 'required|integer',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:10000'
            ];
            $this->validate($req, $rules);
            $userObj = new User;
            $itemObj=new Item;
            $itemName = $item->name;
            // store add on

            $item = $itemObj->updateItem($data, $item);
            if (isset($req->images)) {
                foreach ($req->images as $image) {
                    $source = $userObj->uploadFile($image);
                    ItemImage::createItemImage($source,$item->id);
                }
            }
            if (isset($req->remove_images)) {
                $removeImages = explode(',', $req->remove_images);
                if (count($removeImages) > 0) {
                    foreach ($removeImages as $id) {
                        ItemImage::deleteItemImage($id,$item->id);
                    }
                }
            }

            if (isset($req->services)) {
                foreach ($req->services as $service) {
                    if (isset($service)) {
                        $newObj = new ItemServices();
                        $dataRow = [];
                        $dataRow['item_id'] = $item->id;
                        $dataRow['admin_id'] = auth()->id();
                        $dataRow['service'] = $service;
                        $dataRow['service_type'] = 'services';
                        $newObj->createService($dataRow);
                    }
                }
            }
            if (isset($req->remove_services)) {
                $removeImages = explode(',', $req->remove_services);
                if (count($removeImages) > 0) {
                    foreach ($removeImages as $id) {
                        $service=ItemServices::find($id);
                        if ($service){
                            $service->delete();
                        }
                    }
                }
            }

            if (isset($req->staff)) {
                foreach ($req->staff as $service) {
                    if (isset($service)) {
                        $newObj = new ItemServices();
                        $dataRow = [];
                        $dataRow['item_id'] = $item->id;
                        $dataRow['admin_id'] = auth()->id();
                        $dataRow['service'] = $service;
                        $dataRow['service_type'] = 'staff';
                        $newObj->createService($dataRow);
                    }
                }
            }
            if (isset($req->remove_staff)) {
                $removeImages = explode(',', $req->remove_staff);
                if (count($removeImages) > 0) {
                    foreach ($removeImages as $id) {
                        $service=ItemServices::find($id);
                        if ($service){
                            $service->delete();
                        }
                    }
                }
            }

            if (isset($req->facilities)) {
                foreach ($req->facilities as $service) {
                    if (isset($service)) {
                        $newObj = new ItemServices();
                        $dataRow = [];
                        $dataRow['item_id'] = $item->id;
                        $dataRow['admin_id'] = auth()->id();
                        $dataRow['service'] = $service;
                        $dataRow['service_type'] = 'facilities';
                        $newObj->createService($dataRow);
                    }
                }
            }
            if (isset($req->remove_facilities)) {
                $removeImages = explode(',', $req->remove_facilities);
                if (count($removeImages) > 0) {
                    foreach ($removeImages as $id) {
                        $service=ItemServices::find($id);
                        if ($service){
                            $service->delete();
                        }
                    }
                }
            }

            if (isset($req->offers)) {
                foreach ($req->offers as $service) {
                    if (isset($service)) {
                        $newObj = new ItemServices();
                        $dataRow = [];
                        $dataRow['item_id'] = $item->id;
                        $dataRow['admin_id'] = auth()->id();
                        $dataRow['service'] = $service;
                        $dataRow['service_type'] = 'offers';
                        $newObj->createService($dataRow);
                    }
                }
            }
            if (isset($req->remove_offers)) {
                $removeImages = explode(',', $req->remove_offers);
                if (count($removeImages) > 0) {
                    foreach ($removeImages as $id) {
                        $service=ItemServices::find($id);
                        if ($service){
                            $service->delete();
                        }
                    }
                }
            }



            return redirect()->route('admin.item.index')->with('toast-success', 'Successfully '.$itemName.' details updated!');
        }
        return redirect()->back()->with('toast-error', 'Product not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if ($this->authUser->id != $item->admin_id && $this->authUser->type != 0) {
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }
        $itemName = $item->name;
        // delete images
        $item->images()->delete();
        // delete product
        $item->delete();
        return redirect()->route('admin.item.index')->with('toast-success', 'Successfully '.$itemName.' deleted!');
    }

    public function statusToggle(Item $item)
    {
        if (!(checkValidRestId($this->authUser,$item->restaurant_id))) {
            return redirect('/admin')->with('toast-error', __('Not Authorised'));
        }
        $name = $item->name;
        $restaurant_id = $item->restaurant_id;
        $status = ($item->status)?'InActivated':'Activated';
        $item->status = !$item->status;
        $item->save();
        return redirect()->route('admin.item.index')->with('toast-success', $name.' '.__('Successfully '.$status).'!');
    }
}
