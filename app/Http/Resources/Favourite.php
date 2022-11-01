<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Favourite extends JsonResource
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
            'item_id' =>$this->item_id,
            'user_details'=>($this->user)?new User($this->user):[],
            'item_details'=>($this->item)?new Item($this->item):[]
        ];
    }
}
