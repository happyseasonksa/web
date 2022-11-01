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

class Invitation extends JsonResource
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
            'user_id' => $this->user_id,
            'card_id' => $this->card_id,
            'message' => $this->message,
            'item_name' => $this->item_name,
            'item_id' => $this->item_id,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'invitation_date' => $this->invitation_date,
            'invitation_date_short' => $this->invitation_date?get_friendly_time_ago(strtotime($this->invitation_date)):$this->invitation_date,
            'created_at' => $this->created_at?date('Y-m-d H:i',strtotime($this->created_at)):'',
            'created_at_short' => $this->created_at?get_friendly_time_ago(strtotime($this->created_at)):$this->created_at,
            'signature' => $this->signature,
            'image' => ($this->image)?asset($this->image):null,
            'user_details'=>($this->user)?new User($this->user):[],
            'card_details'=>($this->card)?new Card($this->card):[],
            'invitees' =>(count($this->invitees) > 0)?Invitee::collection($this->invitees):[],
            ];
    }
}
