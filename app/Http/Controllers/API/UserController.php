<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Item as ProductResource;
use App\Http\Resources\Setting;
use App\Models\Card;
use App\Models\City;
use App\Models\Country;
use App\Models\ImageCategory;
use App\Models\Item;
use App\Models\Category;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\Texts;
use CobraProjects\Arabic\Arabic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\City as CityResource;
use App\Http\Resources\Country as CountryResource;
use App\Http\Resources\ImageCategory as ImageCategoryResource;
use App\Http\Resources\Card as CardResource;
use App\Http\Resources\Texts as TextsResource;
use App\Models\Ads;
use App\Http\Resources\Ads as AdsResource;
use App\Http\Resources\SubCategory as SubCategoryResource;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Validator;

class UserController extends BaseController
{
    /**
    *   Create a new controller instance.
    *
    *   @return void
    */

    public function profile()
    {
    	$user = Auth::user();
    	$res = $user->getUserDetailAccType($user);
    	return $this->sendResponse($res, __('api.successful').".");
    }

    public function updateLocal(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'language' => 'required|string|in:en,ar'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $user = Auth::user();
        $user->update(['language'=>$req->language]);
        $res = $user->getUserDetailAccType($user);
        return $this->sendResponse($res, __('api.successful').".");
    }

    public function categoryList()
    {
        $list = Category::all();
        return $this->sendResponse(CategoryResource::collection($list), __('api.successful').".");
    }

    public function subCategoryList(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $list = SubCategory::where('category_id',$req->category_id)->get();
        return $this->sendResponse(SubCategoryResource::collection($list), __('api.successful').".");
    }

    public function citiesList()
    {
        $list=City::all();
        return $this->sendResponse(CityResource::collection($list), __('api.successful').".");

    }
    public function settings()
    {
        $list=\App\Models\Setting::all();
        return $this->sendResponse(Setting::collection($list), __('api.successful').".");

    }
    public function textsList()
    {
        $list=Texts::all();
        return $this->sendResponse(TextsResource::collection($list), __('api.successful').".");

    }

    public function countryList()
    {
        $countries=Country::all();
        return $this->sendResponse(CountryResource::collection($countries), __('api.successful').".");

    }

    public function cardsCategoriesList()
    {
        $countries=ImageCategory::all();
        return $this->sendResponse(ImageCategoryResource::collection($countries), __('api.successful').".");

    }

    public function cardsList(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'category_id' => 'nullable|integer|exists:image_categories,id',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $cards=Card::where('status',1);
        if (isset($req->category_id)){
            $cards=$cards->where('image_category_id',$req->category_id);
        }
        $cards=$cards->get();
        return $this->sendResponse(CardResource::collection($cards), __('api.successful').".");

    }

    public function adsList(Request $req)
    {
            $rules = [
                'provider_id' => 'nullable|integer|exists:admins,id',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->errors());
            }

            $time= date('Y-m-d');
            $ads = Ads::where('end_at','>=',$time)->where('status',1)->get();
            return $this->sendResponse(AdsResource::collection($ads), __('api.successful').".");
    }


    public function productList(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'category_id' => 'required|integer',
            'subcategory_id' => 'nullable|integer|exists:sub_categories,id,status,1',
            'city_id'=>'nullable|integer|exists:cities,id',
            'distance'=>'nullable|string|numeric',
            'rate'=>'nullable|string|numeric',
            'keyword' => 'nullable|string',
            'search_by_distance'=>'nullable|integer:in,0,1',
            'search_by_nearest'=>'nullable|integer:in,0,1',
            'search_by_rating'=>'nullable|integer:in,0,1',
            'lat' => 'required|string|numeric',
            'lng' => 'required|string|numeric',
            'page'   => 'required|integer|min:1',
            'user_id'=>'nullable|integer|exists:users,id'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors());
        }
        $distance = ($req->distance)?:100;//KM
        $latitude = $req->lat;
        $longitude = $req->lng;

//        DB::enableQueryLog();
        $list = Item::leftJoin('reviews', 'items.id', '=', 'reviews.item_id')->groupBy('items.id')
                ->select(\DB::raw('items.*,AVG(reviews.star) AS item_rate, ROUND(( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( items.lat ) ) * cos( radians( items.lng ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( items.lat ) ) ) ),2) AS distance'));
//        $list = Item::join('reviews','reviews.item_id','=','items.id')
//            ->select(\DB::raw('items.*, ROUND(( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( items.lat ) ) * cos( radians( items.lng ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( items.lat ) ) ) ),2) AS distance'));
//        print_r(DB::getQueryLog());
//        die();
        if ($req->category_id !=0) {

            if (isset($req->category_id)) {
                $list = $list->where('category_id', $req->category_id);
            }
            if (isset($req->subcategory_id)) {
                $list = $list->where('subcategory_id', $req->subcategory_id);
            }
        }
        if ($req->keyword) {
            $list = $list->where(function ($q) use ($req) {
                $q->where('name', 'like',"%$req->keyword%")
                    ->orWhere('description', 'like',"%$req->keyword%");
            });
        }
        if ($req->city_id){
            $list=$list->where('city_id',$req->city_id);
        }
        if (isset($req->rate)){
            $list=$list->having('item_rate','>=',$req->rate);
        }
        if (isset($req->search_by_distance) && $req->search_by_distance == 1){
            $list = $list->having('distance', '<=', $distance);
        }
        if (isset($req->search_by_nearest) && $req->search_by_nearest == 1){
            $list = $list->orderBy('distance', 'ASC');
        }
        if (isset($req->search_by_rating) && $req->search_by_rating == 1) {
            $list = $list->orderBy('reviews.star', 'Desc');
        }

        $list=$list->get();
        if(isset($req->user_id)){
            $list->map(function ($item) use ($req) {
                $item->user_id=$req->user_id;
            });
        }
        return $this->sendResponse(ProductResource::collection($list->forpage($req->page,10)), __('api.successful').".", ceil(count($list) / 10),count($list));
    }

    public function productDetail(Request $req,$id)
    {
        $vaidator=Validator::make($req->all(),[
        'user_id'=>'nullable|integer|exists:users,id'
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }

        $product = Item::find($id);
        if ($product) {
            if (isset($req->user_id)) {
                $product->user_id = $req->user_id;
            }
            return $this->sendResponse(new ProductResource($product), __('api.successful').".");
        }
        return $this->sendError(__('api.product_not_found').".", ['error' =>  __('api.product_not_found')."."]);
    }

    public function createImage(Request $req)
    {
        $vaidator=Validator::make($req->all(),[
            'card_id'=>'required|integer|exists:cards,id',
            'message'=>'required|string',
        ]);
        if ($vaidator->fails()){
            return $this->sendError($vaidator->errors()->first(),$vaidator->errors());
        }
        $card=Card::find($req->card_id);
        if ($card){
            $x_axis=$card->x_axis??350;
            $y_axis=$card->y_axis??350;
            $text=$req->message??'';
            $Arabic = new Arabic('Glyphs');
            $text = $Arabic->utf8Glyphs($text);
            $url=explode('documents',$card->image);
            if (count($url) == 2) {
                $img = Image::make(public_path('documents'.$url[1]));
                $img->text($text, $x_axis, $y_axis, function ($font) use ($card) {
                    $size = $card->size ?? 22;
                    $color = $card->color ? '#' . $card->color : '#e1e1e1';
                    $font->file(public_path('css/fonts/Avenir/Neo Sans Arabic Regular.ttf'));
                    $font->size($size);
                    $font->color($color);
                    $font->align('center');
                    $font->valign('bottom');
                    //$font->angle(90);
                });
                $randomImgName = 'card-2022' . rand(1111, 99999) . '.jpg';
                $img->save(public_path('documents/' . $randomImgName));
                $res = [];
                $res['image'] = asset('documents/' . $randomImgName);
                return $this->sendResponse($res, __('api.successful') . ".");
            }
            return $this->sendError(__('This action is unauthorized.').".", ['error' =>  __('This action is unauthorized.')."."]);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->device_token = null;
        $user->save();
        $token = Auth::user()->token();
        if ($token)
            $token->revoke();
        return $this->sendResponse([], __('api.logout_success').".");
    }
}
