<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Allergy;

class Customer extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "language" => $this->language,
            "profile_image" => ($this->profile_image)?asset($this->profile_image):"",
            "city" => $this->city_id?$this->city->name:null,
            "city_id" => $this->city_id,
            "country" => $this->country_id?$this->country->name:null,
            "country_id" => $this->country_id,
            "notify_mute" => $this->notify_mute,
            "notify_type" => $this->notify_type,
            "share_num" => $this->share_num??0,
             "device_type" => $this->device_type,
             "device_token" => $this->device_token,
            "profile_completed" => $this->checkProfile(),
        ];
    }
}
