<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Allergy;

class User extends JsonResource
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
//            "phone_code" => $this->phone_code,
//            "country_code" => $this->country_code,
            "profile_image" => ($this->profile_image)?asset($this->profile_image):"",
            "address" => $this->address,
            "lat" => $this->lat??null,
            "lng" => $this->lng??null,
            "share_num" => $this->share_num??0,
        ];
    }
}
