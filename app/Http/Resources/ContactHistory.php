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

class ContactHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rate=\App\Models\Review::where('user_id',$this->user_id)->where('item_id',$this->item_id)->get();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'item_id' => $this->item_id,
            'provider_id' => $this->admin_id,
            'contact_type' => $this->contact_type,
            'user_details'=>($this->user)?new User($this->user):[],
            'item_details'=>($this->item)?new Item($this->item):[],
            'created_at' =>$this->created_at,
            'created' =>$this->created_at?get_friendly_time_ago(strtotime($this->created_at)):$this->created_at,
            'rated'=>$rate?Review::collection($rate):false,
            ];
    }
}
