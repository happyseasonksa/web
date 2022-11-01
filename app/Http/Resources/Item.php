<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category;
use App\Http\Resources\SubCategory;
use App\Http\Resources\ProductIngredient;
use App\Http\Resources\ProductAddOn;
use App\Http\Resources\ProductRemovable;
use App\Http\Resources\ProductCookingStyle;
use App\Http\Resources\ProductAllergy;
use App\Http\Resources\Restaurant;
use App\Http\Resources\ProductImage;
use App\Models\Favourite;

class Item extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $is_fav = false;
        if (isset($this->user_id)) {
            $fav = Favourite::where('user_id',$this->user_id)->where('item_id', $this->id)->first();
            if ($fav) {
                $is_fav = true;
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'admin_id' => $this->admin_id,
            'city_id' => $this->city_id,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'address' => $this->address,
            'services' => $this->services()?ItemService::collection($this->services()):[],
            'staff' => $this->staff()?ItemService::collection($this->staff()):[],
            'facilities' => $this->facilities()?ItemService::collection($this->facilities()):[],
            'openTimes' => $this->openTimes,
            'offers' => $this->offers()?ItemService::collection($this->offers()):[],
            'website' => $this->website,
            'phone' => $this->phone,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'whatsapp' => $this->whatsapp,
            'status' => $this->status,
            'is_user_fav'=>$is_fav,
            'distance' => ($this->distance)?round($this->distance,2):0,
            'rating' => ($this->item_rate)?round($this->item_rate,2):(($this->reviews)?$this->reviews()->avg('star'):0),
            'rates' => ($this->reviews)?Review::collection($this->reviews):[],
            'city_details' => ($this->city)?new City($this->city):null,
            'category_details' => ($this->category)?new Category($this->category):null,
            'sub_category_details' => ($this->subcategory)?new SubCategory($this->subcategory):null,
            'images' => (count($this->images) > 0)?ItemImage::collection($this->images):null,
        ];
    }
}
