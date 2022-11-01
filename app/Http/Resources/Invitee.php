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

class Invitee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->invitee_id,
            'phone' => $this->phone,
            'user_details'=>($this->invitee)?new User($this->invitee):[],
            ];
    }
}
